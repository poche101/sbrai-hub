import { apiFetch, isLoggedIn, getUser } from './api';

function setBadge(el, verified, label = 'Verified') {
    el.textContent = verified ? `✓ ${label}` : 'Not verified';
    el.classList.toggle('text-green-600', verified);
    el.classList.toggle('text-gray-400', !verified);
}

export function initKycPage() {
    const root = document.getElementById('kyc-page');
    if (!root) return;

    if (!isLoggedIn()) {
        window.location.href = `/auth?next=${encodeURIComponent('/kyc')}`;
        return;
    }

    const progressBar = document.getElementById('kyc-progress-bar');
    const progressLabel = document.getElementById('kyc-progress-label');
    const verifiedBanner = document.getElementById('kyc-verified-banner');
    const emailBadge = document.getElementById('kyc-email-badge');
    const phoneBadge = document.getElementById('kyc-phone-badge');
    const identityBadge = document.getElementById('kyc-identity-badge');
    const cacBadge = document.getElementById('kyc-cac-badge');
    const cacSection = document.getElementById('kyc-cac-section');

    // Business/CAC verification only makes sense for vendor accounts —
    // buyers don't have a business to verify. Hidden by default in the
    // markup; only revealed for vendors.
    if (getUser()?.role === 'vendor') {
        cacSection.classList.remove('hidden');
    }

    async function refreshStatus() {
        try {
            const data = await apiFetch('/kyc/status');
            progressBar.style.width = `${data.progress}%`;
            progressLabel.textContent = `${data.progress}% complete`;
            verifiedBanner.classList.toggle('hidden', data.status !== 'verified');
            setBadge(emailBadge, data.email_verified);
            setBadge(phoneBadge, data.phone_verified);
            setBadge(identityBadge, data.identity_verified);
            setBadge(cacBadge, data.is_verified, 'Business verified');

            document.getElementById('kyc-email-form').classList.toggle('hidden', data.email_verified);
            document.getElementById('kyc-phone-form').classList.toggle('hidden', data.phone_verified);
            document.getElementById('kyc-identity-form').classList.toggle('hidden', data.identity_verified);
        } catch (err) {
            // Non-fatal — leave the page in its last-known state.
        }
    }

    // ── Email OTP ──────────────────────────────────────────────────
    const emailSendBtn = document.getElementById('kyc-email-send');
    const emailVerifyBox = document.getElementById('kyc-email-verify-box');
    const emailOtpInput = document.getElementById('kyc-email-otp');
    const emailError = document.getElementById('kyc-email-error');

    emailSendBtn?.addEventListener('click', async () => {
        emailError.classList.add('hidden');
        emailSendBtn.disabled = true;
        emailSendBtn.textContent = 'Sending…';
        try {
            await apiFetch('/kyc/email/send-otp', { method: 'POST' });
            emailVerifyBox.classList.remove('hidden');
            emailSendBtn.textContent = 'Code sent — resend';
        } catch (err) {
            emailError.textContent = err.message;
            emailError.classList.remove('hidden');
            emailSendBtn.textContent = 'Send code to my email';
        } finally {
            emailSendBtn.disabled = false;
        }
    });

    document.getElementById('kyc-email-verify')?.addEventListener('click', async () => {
        emailError.classList.add('hidden');
        try {
            await apiFetch('/kyc/email/verify', { method: 'POST', json: { otp: emailOtpInput.value } });
            refreshStatus();
        } catch (err) {
            emailError.textContent = err.message;
            emailError.classList.remove('hidden');
        }
    });

    // ── Phone OTP ──────────────────────────────────────────────────
    const phoneSendBtn = document.getElementById('kyc-phone-send');
    const phoneVerifyBox = document.getElementById('kyc-phone-verify-box');
    const phoneOtpInput = document.getElementById('kyc-phone-otp');
    const phoneError = document.getElementById('kyc-phone-error');

    phoneSendBtn?.addEventListener('click', async () => {
        phoneError.classList.add('hidden');
        phoneSendBtn.disabled = true;
        phoneSendBtn.textContent = 'Sending…';
        try {
            await apiFetch('/kyc/phone/send-otp', { method: 'POST' });
            phoneVerifyBox.classList.remove('hidden');
            phoneSendBtn.textContent = 'Code sent — resend';
        } catch (err) {
            phoneError.textContent = err.message;
            phoneError.classList.remove('hidden');
            phoneSendBtn.textContent = 'Send code via SMS';
        } finally {
            phoneSendBtn.disabled = false;
        }
    });

    document.getElementById('kyc-phone-verify')?.addEventListener('click', async () => {
        phoneError.classList.add('hidden');
        try {
            await apiFetch('/kyc/phone/verify', { method: 'POST', json: { otp: phoneOtpInput.value } });
            refreshStatus();
        } catch (err) {
            phoneError.textContent = err.message;
            phoneError.classList.remove('hidden');
        }
    });

    // ── Identity ─────────────────────────────────────────────────────
    const identityType = document.getElementById('kyc-identity-type');
    const identityError = document.getElementById('kyc-identity-error');

    identityType?.addEventListener('change', () => {
        document.querySelectorAll('[data-identity-fields]').forEach((el) => {
            el.classList.toggle('hidden', el.dataset.identityFields !== identityType.value);
        });
    });

    document.getElementById('kyc-identity-submit')?.addEventListener('click', async () => {
        identityError.classList.add('hidden');
        const type = identityType.value;
        const endpoints = {
            nin: '/kyc/identity/nin',
            bvn: '/kyc/identity/bvn',
            drivers_license: '/kyc/identity/drivers-license',
            passport: '/kyc/identity/passport',
        };
        const fieldsWrap = document.querySelector(`[data-identity-fields="${type}"]`);
        const payload = {};
        fieldsWrap.querySelectorAll('[data-field]').forEach((input) => {
            payload[input.dataset.field] = input.value;
        });

        try {
            await apiFetch(endpoints[type], { method: 'POST', json: payload });
            refreshStatus();
        } catch (err) {
            identityError.textContent = err.message;
            identityError.classList.remove('hidden');
        }
    });

    // ── CAC (business) ────────────────────────────────────────────
    const cacError = document.getElementById('kyc-cac-error');
    const cacSuccess = document.getElementById('kyc-cac-success');

    document.getElementById('kyc-cac-submit')?.addEventListener('click', async () => {
        cacError.classList.add('hidden');
        cacSuccess.classList.add('hidden');
        const cacNumber = document.getElementById('kyc-cac-number').value.trim();
        if (!cacNumber) return;

        try {
            const data = await apiFetch('/kyc/business/cac', { method: 'POST', json: { cac_number: cacNumber } });
            cacSuccess.textContent = data.message;
            cacSuccess.classList.remove('hidden');
            refreshStatus();
        } catch (err) {
            cacError.textContent = err.message;
            cacError.classList.remove('hidden');
        }
    });

    refreshStatus();
}
