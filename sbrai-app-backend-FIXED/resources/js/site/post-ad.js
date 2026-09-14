import { apiFetch, isLoggedIn } from './api';

export function initPostAdPage() {
    const root = document.getElementById('post-ad-page');
    if (!root) return;

    const gateView = document.getElementById('post-ad-gate');
    const wizard = document.getElementById('post-ad-wizard');
    const gateMessage = document.getElementById('post-ad-gate-message');

    if (!isLoggedIn()) {
        window.location.href = `/auth?next=${encodeURIComponent('/post-ad')}`;
        return;
    }

    const steps = Array.from(document.querySelectorAll('[data-step]'));
    const stepDots = Array.from(document.querySelectorAll('[data-step-dot]'));
    let currentStep = 1;
    let selectedType = 'product';
    const selectedImages = [];

    function showGate(message, cta = null) {
        gateMessage.textContent = message;
        const ctaEl = document.getElementById('post-ad-gate-cta');
        if (cta) {
            ctaEl.textContent = cta.label;
            ctaEl.href = cta.href;
            ctaEl.classList.remove('hidden');
        } else {
            ctaEl.classList.add('hidden');
        }
        gateView.classList.remove('hidden');
        wizard.classList.add('hidden');
    }

    function goToStep(n) {
        currentStep = n;
        steps.forEach((el) => el.classList.toggle('hidden', Number(el.dataset.step) !== n));
        stepDots.forEach((el) => {
            const dotStep = Number(el.dataset.stepDot);
            el.classList.toggle('sbrai-step-active', dotStep === n);
            el.classList.toggle('sbrai-step-done', dotStep < n);
        });
    }

    async function checkGate() {
        try {
            const me = await apiFetch('/auth/me');
            const user = me.user;

            if (user.role !== 'vendor') {
                showGate('Only vendor accounts can post listings. You signed up as a buyer — contact support if you meant to sell on Sbrai.');
                return false;
            }
            if (user.kyc_status !== 'verified') {
                showGate(
                    'Complete identity verification (KYC) before posting a listing.',
                    { label: 'Verify my identity', href: '/kyc' }
                );
                return false;
            }

            const sub = await apiFetch('/subscriptions/status');
            if (!sub.can_post) {
                showGate(
                    'An active subscription (₦20,000/year via Paystack, or 10 Espees/year) is required to post listings.',
                    { label: 'View subscription options', href: '/pricing' }
                );
                return false;
            }

            wizard.classList.remove('hidden');
            gateView.classList.add('hidden');
            return true;
        } catch (err) {
            showGate(err.message || 'Could not verify your posting eligibility. Please try again.');
            return false;
        }
    }

    // ── Step 1: type + category ─────────────────────────────────────
    document.querySelectorAll('[data-type-option]').forEach((btn) => {
        btn.addEventListener('click', () => {
            selectedType = btn.dataset.typeOption;
            document.querySelectorAll('[data-type-option]').forEach((b) => b.classList.remove('sbrai-option-active'));
            btn.classList.add('sbrai-option-active');
            document.getElementById('field-type').value = selectedType;

            // Property-only attribute fields only make sense for that type.
            document.getElementById('property-attributes')?.classList.toggle('hidden', selectedType !== 'property');
        });
    });

    document.getElementById('step1-next')?.addEventListener('click', () => {
        const category = document.getElementById('field-category').value;
        if (!category) {
            alert('Choose a category to continue.');
            return;
        }
        goToStep(2);
    });

    // ── Step 2: photos (up to 5) ─────────────────────────────────────
    const photoInput = document.getElementById('field-photos');
    const photoPreview = document.getElementById('photo-preview');

    function renderPreviews() {
        photoPreview.innerHTML = selectedImages
            .map((file, i) => `
                <div class="relative">
                    <img src="${URL.createObjectURL(file)}" class="w-full h-24 object-cover rounded-md">
                    <button type="button" data-remove-photo="${i}" class="absolute -top-2 -right-2 bg-white rounded-full w-6 h-6 shadow text-xs">✕</button>
                </div>`)
            .join('');
    }

    photoInput?.addEventListener('change', () => {
        for (const file of photoInput.files) {
            if (selectedImages.length >= 5) break;
            selectedImages.push(file);
        }
        photoInput.value = '';
        renderPreviews();
    });

    photoPreview?.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-remove-photo]');
        if (!btn) return;
        selectedImages.splice(Number(btn.dataset.removePhoto), 1);
        renderPreviews();
    });

    document.getElementById('step2-back')?.addEventListener('click', () => goToStep(1));
    document.getElementById('step2-next')?.addEventListener('click', () => goToStep(3));

    // ── Step 3: details + submit ──────────────────────────────────────
    const form = document.getElementById('post-ad-form');
    document.getElementById('step3-back')?.addEventListener('click', () => goToStep(2));

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = form.querySelector('[type="submit"]');
        const errorBox = document.getElementById('post-ad-error');
        errorBox.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Publishing…';

        const fd = new FormData(form);
        const attributes = {};
        if (selectedType === 'property') {
            ['bedrooms', 'bathrooms', 'furnishing'].forEach((key) => {
                const val = fd.get(key);
                if (val) attributes[key] = val;
            });
        }

        try {
            const created = await apiFetch('/listings', {
                method: 'POST',
                json: {
                    title: fd.get('title'),
                    description: fd.get('description'),
                    price: fd.get('price'),
                    price_unit: fd.get('price_unit'),
                    category: fd.get('category'),
                    type: selectedType,
                    location: fd.get('location'),
                    state: fd.get('state'),
                    attributes,
                },
            });

            if (selectedImages.length) {
                const imgFd = new FormData();
                selectedImages.forEach((file) => imgFd.append('images[]', file));
                await apiFetch(`/listings/${created.listing.id}/images`, { method: 'POST', body: imgFd });
            }

            window.location.href = `/listing/${created.listing.id}`;
        } catch (err) {
            errorBox.textContent = err.message;
            errorBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Publish listing';
        }
    });

    checkGate();
    goToStep(1);
}
