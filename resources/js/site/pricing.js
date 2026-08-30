import { apiFetch, isLoggedIn, getUser } from './api';

function nairaFmt(amount) {
    return '₦' + Number(amount).toLocaleString('en-NG');
}

export function initPricingPage() {
    const root = document.getElementById('pricing-page');
    if (!root) return;

    if (!isLoggedIn()) {
        window.location.href = `/auth?next=${encodeURIComponent('/pricing')}`;
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
    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            tabs.forEach((t) => {
                const active = t === tab;
                t.classList.toggle('border-orange-600', active);
                t.classList.toggle('text-orange-700', active);
                t.classList.toggle('border-transparent', !active);
                t.classList.toggle('text-gray-500', !active);
            });
            panels.forEach((p) => p.classList.toggle('hidden', p.dataset.payPanel !== tab.dataset.payTab));
        });
    });

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
                        <p class="text-gray-900">${t.description}</p>
                        <p class="text-xs text-gray-400">${new Date(t.created_at).toLocaleDateString('en-NG')}</p>
                    </div>
                    <p class="font-medium">${t.currency === 'NGN' ? nairaFmt(t.amount) : `${t.amount} ${t.currency}`}</p>
                </div>`
                )
                .join('');
        } catch (err) {
            listEl.innerHTML = `<p class="text-red-500">${err.message}</p>`;
        }
    }

    // ── Paystack ─────────────────────────────────────────────────────
    (async () => {
        try {
            const data = await apiFetch('/subscriptions/paystack/checkout');
            document.getElementById('paystack-checkout-link').href = data.checkout_url;
        } catch (err) {
            // Leave the link as '#' — the form below still works if they
            // already have a reference from elsewhere.
        }
    })();

    document.getElementById('paystack-verify-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const errorEl = document.getElementById('paystack-error');
        const successEl = document.getElementById('paystack-success');
        errorEl.classList.add('hidden');
        successEl.classList.add('hidden');

        const reference = document.getElementById('paystack-reference').value.trim();
        try {
            const data = await apiFetch('/subscriptions/paystack/verify', { method: 'POST', json: { reference } });
            successEl.textContent = data.message;
            successEl.classList.remove('hidden');
            loadStatus();
            loadTransactions();
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
        }
    });

    // ── Espees ───────────────────────────────────────────────────────
    // Real flow (per Espees' own docs): we create a "product" server-side
    // to get a payment_ref + hosted checkout URL, open that for the user
    // to pay on Espees' own portal (their PIN never touches this site),
    // then confirm the payment_ref server-side. Unlike Paystack, we
    // already know the payment_ref before the user even pays — no manual
    // paste-back needed.
    let espeesPaymentRef = null;
    const espeesErrorEl = document.getElementById('espees-error');
    const espeesSuccessEl = document.getElementById('espees-success');
    const espeesVerifyBox = document.getElementById('espees-verify-box');

    document.getElementById('espees-checkout-btn')?.addEventListener('click', async (e) => {
        const btn = e.target;
        espeesErrorEl.classList.add('hidden');
        espeesSuccessEl.classList.add('hidden');
        btn.disabled = true;
        btn.textContent = 'Starting checkout…';

        try {
            const data = await apiFetch('/subscriptions/espees/checkout');
            espeesPaymentRef = data.payment_ref;
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

    document.getElementById('espees-verify-btn')?.addEventListener('click', async (e) => {
        const btn = e.target;
        espeesErrorEl.classList.add('hidden');
        espeesSuccessEl.classList.add('hidden');

        if (!espeesPaymentRef) {
            espeesErrorEl.textContent = 'Start checkout first.';
            espeesErrorEl.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Verifying…';

        try {
            const data = await apiFetch('/subscriptions/espees/verify', {
                method: 'POST',
                json: { payment_ref: espeesPaymentRef },
            });
            espeesSuccessEl.textContent = data.message;
            espeesSuccessEl.classList.remove('hidden');
            espeesVerifyBox.classList.add('hidden');
            loadStatus();
            loadTransactions();
        } catch (err) {
            espeesErrorEl.textContent = err.message;
            espeesErrorEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.textContent = "I've paid — verify now";
        }
    });

    loadStatus();
    loadTransactions();
}
