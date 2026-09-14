import { apiFetch, isLoggedIn } from './api';

export function initSettingsPage() {
    const root = document.getElementById('settings-page');
    if (!root) return;

    if (!isLoggedIn()) {
        window.location.href = `/auth?next=${encodeURIComponent('/settings')}`;
        return;
    }

    const form = document.getElementById('settings-form');
    const errorBox = document.getElementById('settings-error');
    const successBox = document.getElementById('settings-success');

    function fillForm(settings) {
        form.querySelectorAll('input[type="checkbox"]').forEach((input) => {
            const [group, key] = input.name.split('.');
            input.checked = !!settings?.[group]?.[key];
        });
    }

    function readForm() {
        const settings = { notifications: {}, privacy: {} };
        form.querySelectorAll('input[type="checkbox"]').forEach((input) => {
            const [group, key] = input.name.split('.');
            settings[group][key] = input.checked;
        });
        return settings;
    }

    async function load() {
        try {
            const data = await apiFetch('/settings');
            fillForm(data.settings);
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

        try {
            await apiFetch('/settings', { method: 'PUT', json: readForm() });
            successBox.textContent = 'Settings saved.';
            successBox.classList.remove('hidden');
        } catch (err) {
            errorBox.textContent = err.message;
            errorBox.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
        }
    });

    load();
}
