import { apiFetch, setSession } from './api';

function showError(container, message) {
    container.textContent = message;
    container.classList.remove('hidden');
}

function clearError(container) {
    container.textContent = '';
    container.classList.add('hidden');
}

function redirectAfterAuth(user) {
    const params = new URLSearchParams(window.location.search);
    const next = params.get('next');
    if (next) {
        window.location.href = next;
        return;
    }
    window.location.href = user.role === 'vendor' ? '/post-ad' : '/browse';
}

function initTabs() {
    const tabs = document.querySelectorAll('[data-auth-tab]');
    const panels = document.querySelectorAll('[data-auth-panel]');

    function activate(name) {
        tabs.forEach((t) => {
            const active = t.dataset.authTab === name;
            t.classList.toggle('border-primary', active);
            t.classList.toggle('text-primary', active);
            t.classList.toggle('border-transparent', !active);
            t.classList.toggle('text-muted-foreground', !active);
        });
        panels.forEach((p) => p.classList.toggle('hidden', p.dataset.authPanel !== name));
        const url = new URL(window.location);
        url.searchParams.set('mode', name === 'register' ? 'register' : 'login');
        window.history.replaceState({}, '', url);
    }

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => activate(tab.dataset.authTab));
    });
}

function initRoleToggle() {
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const vendorFields = document.getElementById('vendor-fields');
    if (!vendorFields) return;

    function sync() {
        const selected = document.querySelector('input[name="role"]:checked');
        vendorFields.classList.toggle('hidden', !selected || selected.value !== 'vendor');
    }
    roleInputs.forEach((el) => el.addEventListener('change', sync));
    sync();
}

function initLoginForm() {
    const form = document.getElementById('login-form');
    if (!form) return;
    const errorBox = form.querySelector('[data-form-error]');
    const submitBtn = form.querySelector('[type="submit"]');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearError(errorBox);
        submitBtn.disabled = true;
        submitBtn.textContent = 'Signing in…';

        const fd = new FormData(form);
        try {
            const data = await apiFetch('/auth/login', {
                method: 'POST',
                json: { email: fd.get('email'), password: fd.get('password') },
            });
            setSession(data.token, data.user);
            redirectAfterAuth(data.user);
        } catch (err) {
            showError(errorBox, err.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Sign in';
        }
    });
}

function initRegisterForm() {
    const form = document.getElementById('register-form');
    if (!form) return;
    const errorBox = form.querySelector('[data-form-error]');
    const submitBtn = form.querySelector('[type="submit"]');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearError(errorBox);

        const fd = new FormData(form);
        if (fd.get('password') !== fd.get('password_confirmation')) {
            showError(errorBox, 'Passwords do not match.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating account…';

        try {
            const data = await apiFetch('/auth/register', {
                method: 'POST',
                json: {
                    full_name: fd.get('full_name'),
                    email: fd.get('email'),
                    phone: fd.get('phone'),
                    password: fd.get('password'),
                    password_confirmation: fd.get('password_confirmation'),
                    role: fd.get('role'),
                    business_name: fd.get('business_name') || null,
                    business_address: fd.get('business_address') || null,
                    cac_number: fd.get('cac_number') || null,
                },
            });
            setSession(data.token, data.user);
            redirectAfterAuth(data.user);
        } catch (err) {
            showError(errorBox, err.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create account';
        }
    });
}

function initGoogleSignIn() {
    const mounts = ['google-signin-btn-login', 'google-signin-btn']
        .map((id) => document.getElementById(id))
        .filter(Boolean);
    if (!mounts.length) return;

    const clientId = mounts[0].dataset.clientId;
    if (!clientId) return; // Not configured — containers stay empty.

    const script = document.createElement('script');
    script.src = 'https://accounts.google.com/gsi/client';
    script.async = true;
    script.onload = () => {
        window.google.accounts.id.initialize({
            client_id: clientId,
            callback: async (response) => {
                const role = document.querySelector('input[name="role"]:checked')?.value || 'buyer';
                try {
                    const data = await apiFetch('/auth/google', {
                        method: 'POST',
                        json: { id_token: response.credential, role },
                    });
                    setSession(data.token, data.user);
                    redirectAfterAuth(data.user);
                } catch (err) {
                    alert(err.message);
                }
            },
        });
        // Render into every mount point present on the page (login panel,
        // register panel, or both) — Google draws its own button/logo into
        // each container, so no leftover custom markup is needed there.
        mounts.forEach((mount) => {
            window.google.accounts.id.renderButton(mount, { theme: 'outline', size: 'large', width: 320 });
        });
    };
    document.head.appendChild(script);
}

function initFacebookSignIn() {
    const mount = document.getElementById('facebook-signin-btn');
    const appId = mount?.dataset.appId;
    if (!mount || !appId) return; // Not configured — button stays hidden.

    window.fbAsyncInit = function () {
        window.FB.init({ appId, cookie: false, xfbml: false, version: 'v19.0' });
        mount.classList.remove('hidden');
    };

    const script = document.createElement('script');
    script.src = 'https://connect.facebook.net/en_US/sdk.js';
    script.async = true;
    document.head.appendChild(script);

    mount.addEventListener('click', () => {
        window.FB.login(
            (response) => {
                if (response.authResponse?.accessToken) {
                    handleFacebookLogin(response.authResponse.accessToken);
                }
            },
            { scope: 'public_profile,email' }
        );
    });
}

async function handleFacebookLogin(accessToken) {
    const role = document.querySelector('input[name="role"]:checked')?.value || 'buyer';
    try {
        const data = await apiFetch('/auth/facebook', {
            method: 'POST',
            json: { access_token: accessToken, role },
        });
        setSession(data.token, data.user);
        redirectAfterAuth(data.user);
    } catch (err) {
        alert(err.message);
    }
}

export function initAuthPage() {
    initTabs();
    initRoleToggle();
    initLoginForm();
    initRegisterForm();
    initGoogleSignIn();
    initFacebookSignIn();
}
