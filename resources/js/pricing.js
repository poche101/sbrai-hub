import { apiFetch, isLoggedIn, getUser } from './api';

const ESPEES_REF_KEY = 'sbrai_espees_ref';

function nairaFmt(amount) {
    return '₦' + Number(amount).toLocaleString('en-NG');
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
    }[c]));
}

export function initPricingPage() {
    const root = document.getElementById('pricing-page');
    if (!root) return;

    if (!isLoggedIn()) {
        // Keep the query string so a payment return (?reference=...) survives login.
        const next = window.location.pathname + window.location.search;
        window.location.href = `/auth?next=${encodeURIComponent(next)}`;
        return;
    }

    if (getUser()?.role !== 'vendor') {
        root.innerHTML = `
            <div class="border border-amber-200 bg-amber-50 rounded-lg p-6 text-center">
                <p class="text-amber-800">Subscriptions are for vendor accounts. You're signed in as a buyer.</p>
                <a href="/browse" class="inline-block mt-4 text-sm font-medium text-orange-700 hover:underline">Back to browsing</a>
            </div>`;
        return;
    }

    // ── Tabs ───────────────────────────────────────────────────────
    const tabs = document.querySelectorAll('[data-pay-tab]');
    const panels = document.querySelectorAll('[data-pay-panel]');

    function showTab(name) {
        tabs.forEach((t) => {
            const active = t.dataset.payTab === name;
            ['bg-white', 'text-orange-600', 'shadow-sm'].forEach((c) => t.classList.toggle(c, active));
            ['text-gray-500', 'hover:text-gray-900'].forEach((c) => t.classList.toggle(c, !active));
        });
        panels.forEach((p) => p.classList.toggle('hidden', p.dataset.payPanel !== name));
    }

    tabs.forEach((tab) => tab.addEventListener('click', () => showTab(tab.dataset.payTab)));

    // ── Status + voucher balance ─────────────────────────────────────
    async function loadStatus() {
        try {
            const data = await apiFetch('/subscriptions/status');
            const active = !!data.subscription;
            document.getElementById('sub-status-active').classList.toggle('hidden', !active);
            document.getElementById('sub-status-inactive').classList.toggle('hidden', active);
            if (active) {
                document.getElementById('sub-status-end-date').textContent = new Date(
                    data.subscription.end_date
                ).toLocaleDateString('en-NG', { year: 'numeric', month: 'long', day: 'numeric' });
            }
        } catch (err) {
            // Non-fatal.
        }

        try {
            const bal = await apiFetch('/subscriptions/voucher-balance');
            document.getElementById('voucher-balance').textContent = nairaFmt(bal.balance);
        } catch (err) {
            // Non-fatal.
        }
    }

    async function loadTransactions() {
        const listEl = document.getElementById('transactions-list');
        const emptyEl = document.getElementById('transactions-empty');
        try {
            const data = await apiFetch('/subscriptions/transactions');
            const txs = data.data || [];
            emptyEl.classList.toggle('hidden', txs.length > 0);
            listEl.innerHTML = txs
                .map(
                    (t) => `
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <div>
                        <p class="text-gray-900">${escapeHtml(t.description)}</p>
                        <p class="text-xs text-gray-400">${new Date(t.created_at).toLocaleDateString('en-NG')}</p>
                    </div>
                    <p class="font-medium">${t.currency === 'NGN' ? nairaFmt(t.amount) : `${escapeHtml(t.amount)} ${escapeHtml(t.currency)}`}</p>
                </div>`
                )
                .join('');
        } catch (err) {
            listEl.innerHTML = `<p class="text-red-500">${escapeHtml(err.message)}</p>`;
        }
    }

    function refreshAll() {
        loadStatus();
        loadTransactions();
    }

    // ── Paystack ─────────────────────────────────────────────────────
    // Checkout is created on click (not on page load) so we don't create a
    // dangling Paystack transaction every time the page is opened. Paystack
    // sends the vendor back to /payments/callback, which redirects here with
    // ?reference=...; the webhook activates the subscription independently.
    const paystackErrorEl = document.getElementById('paystack-error');
    const paystackSuccessEl = document.getElementById('paystack-success');
    const paystackLink = document.getElementById('paystack-checkout-link');

    function clearPaystackMessages() {
        paystackErrorEl.classList.add('hidden');
        paystackSuccessEl.classList.add('hidden');
    }

    function paystackMessage(el, text) {
        el.textContent = text;
        el.classList.remove('hidden');
    }

    async function verifyPaystack(reference) {
        clearPaystackMessages();
        try {
            const data = await apiFetch('/subscriptions/paystack/verify', {
                method: 'POST',
                json: { reference },
            });
            paystackMessage(paystackSuccessEl, data.message);
            refreshAll();
            return true;
        } catch (err) {
            paystackMessage(paystackErrorEl, err.message);
            return false;
        }
    }

    paystackLink?.addEventListener('click', async (e) => {
        e.preventDefault();
        clearPaystackMessages();

        const original = paystackLink.innerHTML;
        paystackLink.classList.add('opacity-70', 'pointer-events-none');
        paystackLink.textContent = 'Starting checkout…';

        try {
            const data = await apiFetch('/subscriptions/paystack/checkout');
            window.location.href = data.checkout_url;
        } catch (err) {
            paystackMessage(paystackErrorEl, err.message);
            paystackLink.innerHTML = original;
            paystackLink.classList.remove('opacity-70', 'pointer-events-none');
        }
    });

    document.getElementById('paystack-verify-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const reference = document.getElementById('paystack-reference').value.trim();
        await verifyPaystack(reference);
    });

    // ── Espees ───────────────────────────────────────────────────────
    // We create a payment server-side to get a payment_ref + hosted
    // checkout URL, open it so the vendor pays on Espees' own portal, then
    // confirm the payment_ref server-side. The ref is also kept in
    // localStorage so it survives the redirect back to this page.
    let espeesPaymentRef = localStorage.getItem(ESPEES_REF_KEY);
    const espeesErrorEl = document.getElementById('espees-error');
    const espeesSuccessEl = document.getElementById('espees-success');
    const espeesVerifyBox = document.getElementById('espees-verify-box');
    const espeesVerifyBtn = document.getElementById('espees-verify-btn');

    async function verifyEspees() {
        espeesErrorEl.classList.add('hidden');
        espeesSuccessEl.classList.add('hidden');

        if (!espeesPaymentRef) {
            espeesErrorEl.textContent = 'Start checkout first.';
            espeesErrorEl.classList.remove('hidden');
            return;
        }

        if (espeesVerifyBtn) {
            espeesVerifyBtn.disabled = true;
            espeesVerifyBtn.textContent = 'Verifying…';
        }

        try {
            const data = await apiFetch('/subscriptions/espees/verify', {
                method: 'POST',
                json: { payment_ref: espeesPaymentRef },
            });
            espeesSuccessEl.textContent = data.message;
            espeesSuccessEl.classList.remove('hidden');
            espeesVerifyBox.classList.add('hidden');
            localStorage.removeItem(ESPEES_REF_KEY);
            espeesPaymentRef = null;
            refreshAll();
        } catch (err) {
            espeesErrorEl.textContent = err.message;
            espeesErrorEl.classList.remove('hidden');
            espeesVerifyBox.classList.remove('hidden');
        } finally {
            if (espeesVerifyBtn) {
                espeesVerifyBtn.disabled = false;
                espeesVerifyBtn.textContent = "I've paid — verify now";
            }
        }
    }

    document.getElementById('espees-checkout-btn')?.addEventListener('click', async (e) => {
        const btn = e.currentTarget;
        espeesErrorEl.classList.add('hidden');
        espeesSuccessEl.classList.add('hidden');
        btn.disabled = true;
        btn.textContent = 'Starting checkout…';

        try {
            const data = await apiFetch('/subscriptions/espees/checkout');
            espeesPaymentRef = data.payment_ref;
            localStorage.setItem(ESPEES_REF_KEY, data.payment_ref);
            window.open(data.checkout_url, '_blank', 'noopener');
            espeesVerifyBox.classList.remove('hidden');
        } catch (err) {
            espeesErrorEl.textContent = err.message;
            espeesErrorEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Pay 10 Espees';
        }
    });

    espeesVerifyBtn?.addEventListener('click', verifyEspees);

    // ── Returning from a payment page ────────────────────────────────
    const params = new URLSearchParams(window.location.search);
    const returnedReference = params.get('reference') || params.get('trxref');
    const espeesReturn = params.get('espees');

    if (returnedReference || espeesReturn) {
        // Clean the URL so a refresh doesn't re-trigger verification.
        window.history.replaceState({}, '', '/pricing');
    }

    refreshAll();

    if (returnedReference) {
        showTab('paystack');
        paystackMessage(paystackSuccessEl, 'Confirming your payment…');
        verifyPaystack(returnedReference).then((ok) => {
            if (!ok) {
                // The webhook may still activate it; the status refresh picks that up.
                setTimeout(refreshAll, 5000);
            }
        });
    } else if (espeesReturn === 'success') {
        showTab('espees');
        if (espeesPaymentRef) {
            espeesVerifyBox.classList.remove('hidden');
            verifyEspees();
        }
    } else if (espeesReturn === 'failed') {
        showTab('espees');
        espeesErrorEl.textContent = 'The Espees payment was cancelled or failed. You can try again.';
        espeesErrorEl.classList.remove('hidden');
    } else if (espeesPaymentRef) {
        // Vendor started an Espees checkout earlier and came back later.
        espeesVerifyBox.classList.remove('hidden');
    }
}
