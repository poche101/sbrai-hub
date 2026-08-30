<x-site-layout title="My Profile — Sbrai Solutions" page="profile">

    <div id="profile-page" class="max-w-3xl mx-auto px-4 sm:px-6 py-10 sm:py-14">

        {{-- ── Page header ─────────────────────────────────────────── --}}
        <p class="text-xs font-bold uppercase tracking-wider text-orange-600 mb-2">Account</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight mb-1.5" data-i18n="myProfile">My Profile</h1>
        <p class="text-sm text-gray-500 mb-8">
            Update your account details<span id="profile-role-note" class="hidden"> — vendor-specific fields appear below</span>.
        </p>

        {{-- ── Identity card — avatar + completeness ring (signature element) ── --}}
        <div class="bg-white rounded-3xl border-2 border-gray-300 shadow-sm shadow-gray-200/60 p-6 sm:p-7 mb-6 flex items-center gap-5">

            {{-- Completeness ring: a conic-gradient border around the avatar that
                 fills as the required fields are completed. Echoes the site's own
                 "Verified & Reliable" trust language rather than being pure decoration. --}}
            <div class="relative shrink-0">
                <div id="completeness-ring" class="w-[92px] h-[92px] rounded-full p-[3px] transition-[background]" style="background: conic-gradient(#ea580c calc(var(--pct, 0) * 1%), #f1f5f9 0);">
                    <div class="w-full h-full rounded-full bg-white p-[3px]">
                        <div id="avatar-preview" class="w-full h-full rounded-full bg-gray-100 overflow-hidden flex items-center justify-center text-gray-400 text-2xl font-bold">
                            ?
                        </div>
                    </div>
                </div>
                <label class="absolute -bottom-0.5 -right-0.5 w-8 h-8 rounded-full bg-orange-600 text-white flex items-center justify-center cursor-pointer shadow-md ring-4 ring-white hover:bg-orange-700 transition">
                    <i class="ti ti-camera text-[15px]" aria-hidden="true"></i>
                    <input id="avatar-input" type="file" accept="image/*" class="hidden">
                </label>
            </div>

            <div class="min-w-0">
                <p id="avatar-status" class="text-sm text-gray-500"></p>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <span id="completeness-label" class="text-xs font-semibold text-orange-700 bg-orange-50 px-2 py-1 rounded-full">0% complete</span>
                </div>
                <p class="text-xs text-gray-400 mt-2">JPG or PNG. Square images look best.</p>
            </div>
        </div>

        {{-- ── Personal information card ───────────────────────────── --}}
        <form id="profile-form" class="bg-white rounded-3xl border-2 border-gray-300 shadow-sm shadow-gray-200/60 p-6 sm:p-8 space-y-5 mb-6">

            <div class="flex items-center gap-2.5 pb-1">
                <span class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                    <i class="ti ti-user text-[16px]" aria-hidden="true"></i>
                </span>
                <h2 class="font-semibold text-gray-900">Personal information</h2>
            </div>

            <p id="profile-error" class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-2xl p-3"></p>
            <p id="profile-success" class="hidden text-sm text-green-600 bg-green-50 border border-green-200 rounded-2xl p-3"></p>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full name</label>
                <div class="relative">
                    <i class="ti ti-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[17px]" aria-hidden="true"></i>
                    <input name="full_name" type="text" required placeholder="e.g. Adaeze Okonkwo"
                           class="w-full rounded-xl border-2 border-gray-300 pl-11 py-3.5 text-base text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                <div class="relative">
                    <i class="ti ti-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[17px]" aria-hidden="true"></i>
                    <input name="phone" type="tel" placeholder="e.g. 0803 123 4567"
                           class="w-full rounded-xl border-2 border-gray-300 pl-11 py-3.5 text-base text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <div class="relative">
                    <i class="ti ti-mail absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[17px]" aria-hidden="true"></i>
                    <input id="profile-email" type="email" disabled
                           class="w-full rounded-xl border-2 border-gray-200 pl-11 py-3.5 text-base bg-gray-50 text-gray-400">
                </div>
                <p class="text-xs text-gray-400 mt-1.5">Email can't be changed here.</p>
            </div>

            {{-- Vendor-only sub-section — stays inside #profile-form so it
                 submits with the rest of the fields. Visually set apart with
                 an inset panel rather than pulled into its own <form>. --}}
            <div id="vendor-fields" class="hidden space-y-4 bg-orange-50/40 border border-orange-100 rounded-2xl p-4 sm:p-5">
                <div class="flex items-center gap-2 text-orange-700">
                    <i class="ti ti-building-store text-[16px]" aria-hidden="true"></i>
                    <p class="text-xs font-bold uppercase tracking-wider">Business details</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Business name</label>
                    <div class="relative">
                        <i class="ti ti-building-store absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[17px]" aria-hidden="true"></i>
                        <input name="business_name" type="text" placeholder="e.g. Okonkwo Building Supplies"
                               class="w-full rounded-xl border-2 border-gray-300 pl-11 py-3.5 text-base text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Business address</label>
                    <div class="relative">
                        <i class="ti ti-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[17px]" aria-hidden="true"></i>
                        <input name="business_address" type="text" placeholder="e.g. 14 Adeola Odeku St, Victoria Island, Lagos"
                               class="w-full rounded-xl border-2 border-gray-300 pl-11 py-3.5 text-base text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition">
                    </div>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm hover:shadow-md transition">
                    <i class="ti ti-device-floppy text-[16px]" aria-hidden="true"></i>
                    Save changes
                </button>
            </div>
        </form>

        {{-- ── Security card ───────────────────────────────────────── --}}
        <div class="bg-white rounded-3xl border-2 border-gray-300 shadow-sm shadow-gray-200/60 p-6 sm:p-8">
            <div class="flex items-center gap-2.5 pb-1 mb-4">
                <span class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                    <i class="ti ti-lock text-[16px]" aria-hidden="true"></i>
                </span>
                <h2 class="font-semibold text-gray-900" data-i18n="changePassword">Change Password</h2>
            </div>

            <form id="password-form" class="space-y-4 max-w-sm">
                <p id="password-error" class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-2xl p-3"></p>
                <p id="password-success" class="hidden text-sm text-green-600 bg-green-50 border border-green-200 rounded-2xl p-3"></p>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Current password</label>
                    <div class="relative">
                        <i class="ti ti-key absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[17px]" aria-hidden="true"></i>
                        <input name="current_password" type="password" required
                               class="w-full rounded-xl border-2 border-gray-300 pl-11 py-3.5 text-base text-gray-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">New password</label>
                    <div class="relative">
                        <i class="ti ti-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[17px]" aria-hidden="true"></i>
                        <input name="password" type="password" required minlength="6"
                               class="w-full rounded-xl border-2 border-gray-300 pl-11 py-3.5 text-base text-gray-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition">
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">At least 6 characters.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm new password</label>
                    <div class="relative">
                        <i class="ti ti-lock-check absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[17px]" aria-hidden="true"></i>
                        <input name="password_confirmation" type="password" required minlength="6"
                               class="w-full rounded-xl border-2 border-gray-300 pl-11 py-3.5 text-base text-gray-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition">
                    </div>
                </div>
                <div class="pt-1">
                    <button type="submit" class="inline-flex items-center gap-2 border border-gray-300 font-semibold px-6 py-3 rounded-xl hover:bg-gray-50 transition">
                        <i class="ti ti-shield-check text-[16px]" aria-hidden="true"></i>
                        Update password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Profile completeness ring — additive only, doesn't touch any
         existing submit/upload logic. Recalculates on input and whenever
         #vendor-fields visibility changes (role loads asynchronously). --}}
    <script>
        (function () {
            const ring = document.getElementById('completeness-ring');
            const label = document.getElementById('completeness-label');
            const form = document.getElementById('profile-form');
            const vendorFields = document.getElementById('vendor-fields');
            if (!ring || !label || !form) return;

            function calc() {
                // Email counts as always-complete (it's pre-filled, disabled).
                let total = 1;
                let filled = 1;

                ['full_name', 'phone'].forEach((name) => {
                    const el = form.querySelector(`[name="${name}"]`);
                    if (!el) return;
                    total += 1;
                    if (el.value.trim()) filled += 1;
                });

                if (vendorFields && !vendorFields.classList.contains('hidden')) {
                    ['business_name', 'business_address'].forEach((name) => {
                        const el = form.querySelector(`[name="${name}"]`);
                        if (!el) return;
                        total += 1;
                        if (el.value.trim()) filled += 1;
                    });
                }

                const pct = Math.round((filled / total) * 100);
                ring.style.setProperty('--pct', pct);
                label.textContent = `${pct}% complete`;
            }

            form.addEventListener('input', calc);
            if (vendorFields) {
                new MutationObserver(calc).observe(vendorFields, { attributes: true, attributeFilter: ['class'] });
            }
            calc();
        })();
    </script>

</x-site-layout>
