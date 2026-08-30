import { apiFetch, isLoggedIn, getUser, setSession, getToken } from './api';

export function initProfilePage() {
    const root = document.getElementById('profile-page');
    if (!root) return;

    if (!isLoggedIn()) {
        window.location.href = `/auth?next=${encodeURIComponent('/profile')}`;
        return;
    }

    const form = document.getElementById('profile-form');
    const errorBox = document.getElementById('profile-error');
    const successBox = document.getElementById('profile-success');
    const vendorFields = document.getElementById('vendor-fields');
    const roleNote = document.getElementById('profile-role-note');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarInput = document.getElementById('avatar-input');
    const avatarStatus = document.getElementById('avatar-status');

    function renderAvatar(user) {
        if (user.avatar_url) {
            avatarPreview.innerHTML = `<img src="${user.avatar_url}" class="w-full h-full object-cover" alt="Profile photo">`;
        } else {
            avatarPreview.textContent = (user.full_name || '?').trim().charAt(0).toUpperCase();
        }
    }

    function fillForm(user) {
        form.full_name.value = user.full_name || '';
        form.phone.value = user.phone || '';
        document.getElementById('profile-email').value = user.email || '';

        const isVendor = user.role === 'vendor';
        vendorFields.classList.toggle('hidden', !isVendor);
        roleNote.classList.toggle('hidden', !isVendor);
        if (isVendor) {
            form.business_name.value = user.business_name || '';
            form.business_address.value = user.business_address || '';
        }
        renderAvatar(user);
    }

    async function load() {
        try {
            const data = await apiFetch('/auth/me');
            fillForm(data.user);
        } catch (err) {
            errorBox.textContent = err.message;
            errorBox.classList.remove('hidden');
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorBox.classList.add('hidden');
        successBox.classList.add('hidden');
        const submitBtn = form.querySelector('[type="submit"]');
        submitBtn.disabled = true;

        const payload = {
            full_name: form.full_name.value,
            phone: form.phone.value,
        };
        if (!vendorFields.classList.contains('hidden')) {
            payload.business_name = form.business_name.value;
            payload.business_address = form.business_address.value;
        }

        try {
            const data = await apiFetch('/auth/profile', { method: 'PUT', json: payload });
            setSession(getToken(), data.user); // refresh the cached user (name/role used elsewhere in the nav)
            successBox.textContent = 'Profile updated.';
            successBox.classList.remove('hidden');
        } catch (err) {
            errorBox.textContent = err.message;
            errorBox.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
        }
    });

    avatarInput.addEventListener('change', async () => {
        const file = avatarInput.files[0];
        if (!file) return;
        avatarStatus.textContent = 'Uploading…';

        const fd = new FormData();
        fd.append('avatar', file);

        try {
            const data = await apiFetch('/auth/avatar', { method: 'POST', body: fd });
            const user = getUser();
            user.avatar_url = data.avatar_url;
            setSession(getToken(), user);
            renderAvatar(user);
            avatarStatus.textContent = 'Photo updated.';
        } catch (err) {
            avatarStatus.textContent = err.message;
        }
    });

    // ── Change password ─────────────────────────────────────────────
    const pwForm = document.getElementById('password-form');
    const pwError = document.getElementById('password-error');
    const pwSuccess = document.getElementById('password-success');

    pwForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        pwError.classList.add('hidden');
        pwSuccess.classList.add('hidden');

        if (pwForm.password.value !== pwForm.password_confirmation.value) {
            pwError.textContent = 'New passwords do not match.';
            pwError.classList.remove('hidden');
            return;
        }

        const submitBtn = pwForm.querySelector('[type="submit"]');
        submitBtn.disabled = true;

        try {
            const data = await apiFetch('/auth/change-password', {
                method: 'POST',
                json: {
                    current_password: pwForm.current_password.value,
                    password: pwForm.password.value,
                    password_confirmation: pwForm.password_confirmation.value,
                },
            });
            pwSuccess.textContent = data.message;
            pwSuccess.classList.remove('hidden');
            pwForm.reset();
        } catch (err) {
            pwError.textContent = err.message;
            pwError.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
        }
    });

    load();
}
