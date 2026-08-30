import { apiFetch, isLoggedIn, getUser } from './api';

export function initListingPage() {
    const root = document.getElementById('listing-page');
    if (!root) return;

    const listingId = root.dataset.listingId;
    const vendorId = root.dataset.vendorId;

    const favBtn = document.getElementById('fav-btn');
    const chatBtn = document.getElementById('chat-btn');
    const callBtn = document.getElementById('call-btn');
    const gateNote = document.getElementById('contact-gate-note');
    const composeBox = document.getElementById('chat-compose');
    const composeForm = document.getElementById('chat-compose-form');
    const composeInput = document.getElementById('chat-compose-input');
    const composeError = document.getElementById('chat-compose-error');

    function requireAuth() {
        if (isLoggedIn()) return true;
        window.location.href = `/auth?next=${encodeURIComponent(window.location.pathname)}`;
        return false;
    }

    function passesKycGate() {
        const user = getUser();
        if (user?.kyc_status !== 'verified') {
            gateNote.innerHTML =
                'Verify your identity to chat or call on Sbrai Solutions. <a href="/kyc" class="underline font-medium">Verify now</a>.';
            gateNote.classList.remove('hidden');
            return false;
        }
        return true;
    }

    async function syncFavoriteState() {
        if (!favBtn || !isLoggedIn()) return;
        try {
            const data = await apiFetch('/favorites');
            const ids = (data.data || []).map((f) => f.listing_id ?? f.id);
            favBtn.classList.toggle('sbrai-fav-active', ids.includes(listingId));
        } catch (e) {
            // Non-fatal — leave the button in its default state.
        }
    }

    favBtn?.addEventListener('click', async () => {
        if (!requireAuth()) return;
        favBtn.classList.toggle('sbrai-fav-active');
        try {
            await apiFetch('/favorites/toggle', { method: 'POST', json: { listing_id: listingId } });
        } catch (err) {
            favBtn.classList.toggle('sbrai-fav-active');
            alert(err.message);
        }
    });

    // ── Chat ───────────────────────────────────────────────────────
    chatBtn?.addEventListener('click', () => {
        if (!requireAuth() || !passesKycGate()) return;
        gateNote.classList.add('hidden');
        composeBox.classList.remove('hidden');
        composeInput.focus();
    });

    composeForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = composeInput.value.trim();
        if (!message) return;
        composeError.classList.add('hidden');
        const submitBtn = composeForm.querySelector('[type="submit"]');
        submitBtn.disabled = true;

        try {
            const data = await apiFetch('/chats', {
                method: 'POST',
                json: { vendor_id: vendorId, listing_id: listingId, message },
            });
            window.location.href = `/messages?chat=${data.chat.id}`;
        } catch (err) {
            composeError.textContent = err.message;
            composeError.classList.remove('hidden');
            submitBtn.disabled = false;
        }
    });

    // ── Call ───────────────────────────────────────────────────────
    callBtn?.addEventListener('click', async () => {
        if (!requireAuth() || !passesKycGate()) return;
        gateNote.classList.add('hidden');
        callBtn.disabled = true;
        callBtn.textContent = 'Connecting…';

        try {
            // A chat ties the call to a channel both sides can reason
            // about later (matches the channel-naming scheme in
            // messages.js: `chat-${chatId}`).
            const data = await apiFetch('/chats', {
                method: 'POST',
                json: { vendor_id: vendorId, listing_id: listingId, message: '📞 Started a call' },
            });
            window.dispatchEvent(
                new CustomEvent('sbrai:start-call', {
                    detail: { recipientId: vendorId, chatId: data.chat.id, callType: 'voice' },
                })
            );
        } catch (err) {
            gateNote.textContent = err.message;
            gateNote.classList.remove('hidden');
        } finally {
            callBtn.disabled = false;
            callBtn.textContent = 'Voice / video call';
        }
    });

    syncFavoriteState();
}
