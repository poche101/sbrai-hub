import { getUser, isLoggedIn, clearSession, apiFetch } from './api';

function render() {
    const guestEls = document.querySelectorAll('[data-nav="guest"]');
    const userEls = document.querySelectorAll('[data-nav="user"]');
    const nameEls = document.querySelectorAll('[data-nav="user-name"]');
    const emailEls = document.querySelectorAll('[data-nav="user-email"]');
    const avatarEls = document.querySelectorAll('[data-nav="user-avatar"]');
    const postAdLinks = document.querySelectorAll('[data-nav="post-ad-link"]');

    const loggedIn = isLoggedIn();
    const user = getUser();

    // Same lockstep hidden/flex toggle as userEls below — guestEls' static
    // class list also carries "flex", which is the same trap.
    guestEls.forEach((el) => {
        el.classList.toggle('hidden', loggedIn);
        el.classList.toggle('flex', !loggedIn);
    });

    // IMPORTANT: userEls must never have "hidden" and "flex" on it at the
    // same time — this build's Tailwind output order makes ".flex" beat
    // ".hidden" in the cascade (see the .desktop-auth-cluster comment in
    // site-layout.blade.php). Toggling only "hidden" left the div at its
    // default display:block once shown, which made its <details> children
    // (bell, account) stack vertically instead of sitting in a row.
    // Toggling "flex" in lockstep with "hidden" (never both, never neither)
    // fixes that regardless of what the static class list contains.
    userEls.forEach((el) => {
        el.classList.toggle('hidden', !loggedIn);
        el.classList.toggle('flex', loggedIn);
    });

    if (user) {
        nameEls.forEach((el) => (el.textContent = user.full_name?.split(' ')[0] || 'Account'));
        emailEls.forEach((el) => (el.textContent = user.email || ''));
        avatarEls.forEach((el) => {
            if (user.avatar_url) {
                el.innerHTML = `<img src="${user.avatar_url}" class="w-full h-full object-cover rounded-full" alt="">`;
            } else {
                el.textContent = (user.full_name || user.email || '?').trim().charAt(0).toUpperCase();
            }
        });
    }

    // Only vendors get a direct "Post an Ad" nav shortcut; buyers see it
    // once they switch/are a vendor. Buyers can still reach it and will
    // get a clear gate message explaining why.
    postAdLinks.forEach((el) => {
        el.classList.toggle('hidden', loggedIn && user?.role !== 'vendor');
    });
}

async function logout() {
    try {
        await apiFetch('/auth/logout', { method: 'POST' });
    } catch (e) {
        // Even if the server call fails (e.g. token already expired),
        // still clear the local session so the UI reflects logged-out.
    }
    clearSession();
    window.location.href = '/';
}

function timeAgo(iso) {
    const mins = Math.round((Date.now() - new Date(iso).getTime()) / 60000);
    if (mins < 1) return 'just now';
    if (mins < 60) return `${mins}m ago`;
    const hrs = Math.round(mins / 60);
    if (hrs < 24) return `${hrs}h ago`;
    return `${Math.round(hrs / 24)}d ago`;
}

function initNotifications() {
    const dropdown = document.getElementById('notif-dropdown');
    if (!dropdown || !isLoggedIn()) return;

    const badge = document.getElementById('notif-badge');
    const list = document.getElementById('notif-list');
    const markAllBtn = document.getElementById('notif-mark-all');
    let loaded = false;

    async function refreshBadge() {
        try {
            const data = await apiFetch('/notifications/unread-count');
            badge.textContent = data.count > 9 ? '9+' : String(data.count);
            badge.classList.toggle('hidden', data.count === 0);
        } catch (e) {
            // Non-fatal — badge just stays at its last known state.
        }
    }

    async function loadList() {
        try {
            const data = await apiFetch('/notifications');
            const items = data.data || [];
            if (!items.length) {
                list.innerHTML = '<p class="p-4 text-gray-400 text-xs">No notifications yet.</p>';
                return;
            }
            list.innerHTML = items
                .map(
                    (n) => `
                <button
                    type="button" data-notif-id="${n.id}"
                    class="w-full text-left p-3 hover:bg-gray-50 ${n.is_read ? '' : 'bg-orange-50/60'}"
                >
                    <p class="text-sm font-medium text-gray-900">${n.title}</p>
                    <p class="text-xs text-gray-500 mt-0.5">${n.body}</p>
                    <p class="text-[11px] text-gray-400 mt-1">${timeAgo(n.created_at)}</p>
                </button>`
                )
                .join('');
        } catch (err) {
            list.innerHTML = `<p class="p-4 text-red-500 text-xs">${err.message}</p>`;
        }
    }

    dropdown.addEventListener('toggle', () => {
        if (dropdown.open && !loaded) {
            loaded = true;
            loadList();
        }
    });

    list.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-notif-id]');
        if (!btn) return;
        btn.classList.remove('bg-orange-50/60');
        try {
            await apiFetch(`/notifications/${btn.dataset.notifId}/read`, { method: 'POST' });
            refreshBadge();
        } catch (err) {
            // Non-fatal.
        }
    });

    markAllBtn?.addEventListener('click', async () => {
        try {
            await apiFetch('/notifications/read-all', { method: 'POST' });
            loadList();
            refreshBadge();
        } catch (err) {
            alert(err.message);
        }
    });

    refreshBadge();
    setInterval(refreshBadge, 30000);
}

export function initNav() {
    render();
    window.addEventListener('sbrai:session-changed', render);
    initNotifications();

    document.querySelectorAll('[data-action="logout"]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            logout();
        });
    });
}
