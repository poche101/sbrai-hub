<x-site-layout title="Verify your identity — Sbrai Solutions" page="kyc">

    <div id="kyc-page" class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-xl font-semibold text-gray-900 mb-1">Verify your identity</h1>
        <p class="text-sm text-gray-500 mb-6">
            Complete all three steps below to get your <strong>kyc_status: verified</strong> badge and unlock posting listings, chat, and calls.
        </p>

        <div class="mb-8">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span id="kyc-progress-label">0% complete</span>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div id="kyc-progress-bar" class="h-full bg-orange-600 transition-all" style="width: 0%"></div>
            </div>
        </div>

        <p id="kyc-verified-banner" class="hidden mb-6 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">
            ✓ You're fully verified. You can post listings, chat, and call vendors/buyers.
        </p>

        {{-- ── Step: Email ─────────────────────────────────────────── --}}
        <div class="border-2 border-gray-300 rounded-3xl bg-white shadow-sm shadow-gray-200/60 p-6 mb-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-medium text-gray-900">1. Verify email</h2>
                <span id="kyc-email-badge" class="text-xs text-gray-400">Not verified</span>
            </div>
            <div id="kyc-email-form" class="space-y-2">
                <button id="kyc-email-send" type="button" class="text-sm bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                    Send code to my email
                </button>
                <div id="kyc-email-verify-box" class="hidden flex gap-2 mt-2">
                    <input id="kyc-email-otp" type="text" inputmode="numeric" maxlength="6" placeholder="6-digit code" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500 w-32">
                    <button id="kyc-email-verify" type="button" class="text-sm bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-black">Verify</button>
                </div>
                <p id="kyc-email-error" class="hidden text-xs text-red-600"></p>
            </div>
        </div>

        {{-- ── Step: Phone ─────────────────────────────────────────── --}}
        <div class="border-2 border-gray-300 rounded-3xl bg-white shadow-sm shadow-gray-200/60 p-6 mb-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-medium text-gray-900">2. Verify phone</h2>
                <span id="kyc-phone-badge" class="text-xs text-gray-400">Not verified</span>
            </div>
            <div id="kyc-phone-form" class="space-y-2">
                <button id="kyc-phone-send" type="button" class="text-sm bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                    Send code via SMS
                </button>
                <div id="kyc-phone-verify-box" class="hidden flex gap-2 mt-2">
                    <input id="kyc-phone-otp" type="text" inputmode="numeric" maxlength="6" placeholder="6-digit code" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500 w-32">
                    <button id="kyc-phone-verify" type="button" class="text-sm bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-black">Verify</button>
                </div>
                <p id="kyc-phone-error" class="hidden text-xs text-red-600"></p>
            </div>
        </div>

        {{-- ── Step: Identity ──────────────────────────────────────── --}}
        <div class="border-2 border-gray-300 rounded-3xl bg-white shadow-sm shadow-gray-200/60 p-6 mb-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-medium text-gray-900">3. Verify identity</h2>
                <span id="kyc-identity-badge" class="text-xs text-gray-400">Not verified</span>
            </div>
            <div id="kyc-identity-form" class="space-y-3">
                <select id="kyc-identity-type" class="w-full sm:w-auto rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                    <option value="nin">National ID (NIN)</option>
                    <option value="bvn">Bank Verification Number (BVN)</option>
                    <option value="drivers_license">Driver's license</option>
                    <option value="passport">International passport</option>
                </select>

                <div data-identity-fields="nin" class="flex gap-2">
                    <input type="text" data-field="nin" inputmode="numeric" maxlength="11" placeholder="11-digit NIN" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500 flex-1">
                </div>
                <div data-identity-fields="bvn" class="hidden flex gap-2">
                    <input type="text" data-field="bvn" inputmode="numeric" maxlength="11" placeholder="11-digit BVN" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500 flex-1">
                </div>
                <div data-identity-fields="drivers_license" class="hidden grid grid-cols-2 gap-2">
                    <input type="text" data-field="license_number" placeholder="License number" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                    <input type="date" data-field="date_of_birth" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div data-identity-fields="passport" class="hidden grid grid-cols-3 gap-2">
                    <input type="text" data-field="passport_number" placeholder="Passport number" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                    <input type="text" data-field="last_name" placeholder="Last name" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                    <input type="date" data-field="date_of_birth" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                </div>

                <button id="kyc-identity-submit" type="button" class="text-sm bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                    Verify identity
                </button>
                <p id="kyc-identity-error" class="hidden text-xs text-red-600"></p>
            </div>
        </div>

        {{-- ── Business / CAC — vendor accounts only ────────────────── --}}
        {{-- Removed 'hidden' class by default, styled with dark border to match vendor cards --}}
        <div id="kyc-cac-section" class="border-2 border-gray-300 rounded-3xl bg-white shadow-sm shadow-gray-200/60 p-6 mb-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-medium text-gray-900">Business verification <span class="text-gray-400 font-normal text-sm">(Vendor requirement — adds verified badge)</span></h2>
                <span id="kyc-cac-badge" class="text-xs text-gray-400">Not verified</span>
            </div>
            <div class="flex gap-2">
                <input id="kyc-cac-number" type="text" maxlength="20" placeholder="CAC registration number" class="rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500 flex-1">
                <button id="kyc-cac-submit" type="button" class="text-sm bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-black">Verify</button>
            </div>
            <p id="kyc-cac-error" class="hidden text-xs text-red-600 mt-2"></p>
            <p id="kyc-cac-success" class="hidden text-xs text-green-600 mt-2"></p>
        </div>

        <div class="flex gap-3">
            <a href="/post-ad" class="text-sm font-medium text-orange-700 hover:underline">← Back to Post an Ad</a>
        </div>
    </div>

</x-site-layout>
