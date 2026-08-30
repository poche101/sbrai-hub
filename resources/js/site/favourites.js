import { apiFetch, isLoggedIn } from './api';
import { gradientFor } from './gradient';

function nairaFmt(price) {
    return '₦' + Number(price).toLocaleString('en-NG');
}

function card(listing) {
    const img = listing.image_urls?.[0];
    return `
    <div class="group relative block rounded-lg border border-gray-200 overflow-hidden bg-white hover:shadow-md transition">
        <a href="/listing/${listing.id}" class="block">
            <div class="aspect-[4/3] bg-gray-100">
                ${img
                    ? `<img src="${img}" alt="${listing.title}" class="w-full h-full object-cover">`
                    : `<div class="w-full h-full flex flex-col justify-between p-3" style="background-image: ${gradientFor(listing.category || listing.title)}">
                            <span class="self-start bg-white/90 text-gray-800 text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize">${listing.type || ''}</span>
                            <span class="text-white font-bold drop-shadow">${listing.category || ''}</span>
                       </div>`}
            </div>
            <div class="p-3">
                <p class="font-semibold text-gray-900">${nairaFmt(listing.price)} <span class="font-normal text-gray-500 text-sm">/ ${listing.price_unit}</span></p>
                <p class="text-sm text-gray-800 truncate mt-1">${listing.title}</p>
                <p class="text-xs text-gray-500 mt-1">${listing.location}, ${listing.state}</p>
            </div>
        </a>
        <button
            type="button" data-unfavorite="${listing.id}"
            class="absolute top-2 right-2 w-8 h-8 rounded-full bg-white/90 flex items-center justify-center shadow text-red-500"
            aria-label="Remove from favourites"
        >♥</button>
    </div>`;
}

export function initFavouritesPage() {
    const root = document.getElementById('favourites-page');
    if (!root) return;

    if (!isLoggedIn()) {
        window.location.href = `/auth?next=${encodeURIComponent('/favourites')}`;
        return;
    }

    const grid = document.getElementById('favourites-grid');
    const empty = document.getElementById('favourites-empty');

    async function load() {
        try {
            const data = await apiFetch('/favorites');
            const items = data.data || [];
            empty.classList.toggle('hidden', items.length > 0);
            grid.innerHTML = items.map(card).join('');
        } catch (err) {
            grid.innerHTML = `<p class="col-span-full text-center text-red-500 py-10">${err.message}</p>`;
        }
    }

    grid.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-unfavorite]');
        if (!btn) return;
        e.preventDefault();
        try {
            await apiFetch('/favorites/toggle', { method: 'POST', json: { listing_id: btn.dataset.unfavorite } });
            load();
        } catch (err) {
            alert(err.message);
        }
    });

    load();
}
