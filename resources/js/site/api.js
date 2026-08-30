// Thin wrapper around fetch() for the /api/v1 Laravel Sanctum API.
// The bearer token lives in localStorage (there is no session cookie
// for this API), so every authenticated call attaches it manually.

const TOKEN_KEY = 'sbrai_token';
const USER_KEY = 'sbrai_user';

export function getToken() {
    return localStorage.getItem(TOKEN_KEY);
}

export function getUser() {
    const raw = localStorage.getItem(USER_KEY);
    if (!raw) return null;
    try {
        return JSON.parse(raw);
    } catch (e) {
        return null;
    }
}

export function isLoggedIn() {
    return !!getToken();
}

export function setSession(token, user) {
    localStorage.setItem(TOKEN_KEY, token);
    localStorage.setItem(USER_KEY, JSON.stringify(user));
    window.dispatchEvent(new CustomEvent('sbrai:session-changed'));
}

export function clearSession() {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
    window.dispatchEvent(new CustomEvent('sbrai:session-changed'));
}

/**
 * @param {string} path e.g. '/listings' — will be prefixed with /api/v1
 * @param {RequestInit & { json?: any }} options
 */
export async function apiFetch(path, options = {}) {
    const token = getToken();
    const isFormData = options.body instanceof FormData;

    const headers = {
        Accept: 'application/json',
        ...(isFormData ? {} : { 'Content-Type': 'application/json' }),
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
        ...(options.headers || {}),
    };

    let body = options.body;
    if (!isFormData && options.json !== undefined) {
        body = JSON.stringify(options.json);
    }

    const res = await fetch(`/api/v1${path}`, { ...options, headers, body });

    let data = null;
    const text = await res.text();
    if (text) {
        try {
            data = JSON.parse(text);
        } catch (e) {
            data = null;
        }
    }

    if (res.status === 401) {
        // Token missing/expired — drop it so the UI stops assuming we're
        // signed in.
        clearSession();
    }

    if (!res.ok) {
        const message =
            (data && data.message) ||
            (data && data.errors && Object.values(data.errors)[0]?.[0]) ||
            `Request failed (${res.status})`;
        const error = new Error(message);
        error.status = res.status;
        error.data = data;
        throw error;
    }

    return data;
}
