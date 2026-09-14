import { apiFetch, isLoggedIn } from './api';
import { gradientFor } from './gradient';

function nairaFmt(price) {
    return '₦' + Number(price).toLocaleString('en-NG');
}

function listingCard(listing) {
    const img = listing.image_urls?.[0];
    const favClass = listing.is_favorited ? 'sbrai-fav-active' : '';
    return `
    <a href="/listing/${listing.id}" class="group block rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition bg-white">
        <div class="relative aspect-[4/3] bg-gray-100">
            ${img
                ? `<img src="${img}" alt="${listing.title}" class="w-full h-full object-cover">`
                : `<div class="w-full h-full flex flex-col justify-between p-3" style="background-image: ${gradientFor(listing.category || listing.title)}">
                        <span class="self-start bg-white/90 text-gray-800 text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize">${listing.type || ''}</span>
                        <span class="text-white font-bold drop-shadow">${listing.category || ''}</span>
                   </div>`}
            <button
                type="button"
                data-favorite-toggle="${listing.id}"
                class="sbrai-fav-btn ${favClass} absolute top-2 right-2 w-8 h-8 rounded-full bg-white/90 flex items-center justify-center shadow"
                aria-label="Save listing"
            >♥</button>
        </div>
        <div class="p-3">
            <p class="font-semibold text-gray-900">${nairaFmt(listing.price)} <span class="font-normal text-gray-500 text-sm">/ ${listing.price_unit}</span></p>
            <p class="text-sm text-gray-800 truncate mt-1">${listing.title}</p>
            <p class="text-xs text-gray-500 mt-1">${listing.location}, ${listing.state}</p>
            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                ${listing.vendor_verified ? '<span class="text-green-600">✓ Verified vendor</span>' : listing.vendor_business_name || listing.vendor_name}
            </p>
        </div>
    </a>`;
}

export function initBrowsePage() {
    const form = document.getElementById('browse-filters');
    const results = document.getElementById('browse-results');
    const loadMoreBtn = document.getElementById('browse-load-more');
    const emptyState = document.getElementById('browse-empty');
    const countLabel = document.getElementById('browse-count');
    if (!form || !results) return;

    let page = 1;
    let lastPage = 1;

    function buildParams(extra = {}) {
        const fd = new FormData(form);
        const params = new URLSearchParams();
        for (const [key, val] of fd.entries()) {
            if (val) params.set(key, val);
        }
        params.set('page', extra.page || 1);
        params.set('per_page', 20);
        return params;
    }

    async function toggleFavorite(id, btn) {
        if (!isLoggedIn()) {
            window.location.href = `/auth?next=${encodeURIComponent(window.location.pathname + window.location.search)}`;
            return;
        }
        btn.classList.toggle('sbrai-fav-active');
        try {
            await apiFetch('/favorites/toggle', { method: 'POST', json: { listing_id: id } });
        } catch (err) {
            btn.classList.toggle('sbrai-fav-active'); // revert on failure
            alert(err.message);
        }
    }

    async function load(reset = true) {
        if (reset) {
            page = 1;
            results.innerHTML = '<p class="col-span-full text-center text-gray-400 py-10">Loading…</p>';
        }
        try {
            const params = buildParams({ page });
            const data = await apiFetch(`/listings?${params.toString()}`);
            lastPage = data.meta.last_page;

            const html = data.data.map(listingCard).join('');
            results.innerHTML = reset ? html : results.innerHTML + html;

            emptyState.classList.toggle('hidden', data.meta.total !== 0);
            countLabel.textContent = data.meta.total === 1 ? '1 listing' : `${data.meta.total} listings`;
            loadMoreBtn.classList.toggle('hidden', page >= lastPage);
        } catch (err) {
            results.innerHTML = `<p class="col-span-full text-center text-red-500 py-10">${err.message}</p>`;
        }
    }

    results.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-favorite-toggle]');
        if (!btn) return;
        e.preventDefault();
        toggleFavorite(btn.dataset.favoriteToggle, btn);
    });

    // ── Category chips (single-select pill row) ─────────────────────
    const categoryInput = form.querySelector('input[name="category"]');
    const chips = form.querySelectorAll('[data-category-chip]');

    chips.forEach((chip) => {
        chip.addEventListener('click', () => {
            categoryInput.value = chip.dataset.categoryChip;
            chips.forEach((c) => c.classList.toggle('sbrai-chip-active', c === chip));
            load(true);
        });
    });

    document.getElementById('browse-reset')?.addEventListener('click', () => {
        form.reset();
        categoryInput.value = '';
        form.querySelector('input[name="type"]').value = '';
        chips.forEach((c) => c.classList.toggle('sbrai-chip-active', c.dataset.categoryChip === ''));
        load(true);
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        load(true);
    });
    form.addEventListener('change', (e) => {
        // Ignore changes bubbling from the (JS-managed) hidden category/type
        // inputs — those already trigger their own load() call above.
        if (e.target.name === 'category' || e.target.name === 'type') return;
        load(true);
    });

    loadMoreBtn.addEventListener('click', () => {
        page += 1;
        load(false);
    });

    load(true);
}
