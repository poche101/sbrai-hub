<x-site-layout title="Verify your identity — Sbrai Solutions" page="kyc">

    <div id="kyc-page" class="max-w-2xl mx-auto px-4 sm:px-6 py-10 sm:py-14">

        {{-- Page header --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                <svg width="22" height="22" class="h-[22px] w-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15 9.75m-3-7.036A11.955 11.955 0 013.598 6c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622a11.955 11.955 0 01-8.402-3.036 12.06 12.06 0 01-.599-.396c-.19-.129-.409-.129-.599 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Verify your identity</h1>
                <p class="text-sm text-gray-500">
                    Complete all three steps to get your <strong class="text-gray-700 font-semibold">verified</strong> badge and unlock posting listings, chat, and calls.
                </p>
            </div>
        </div>

        {{-- Progress --}}
        <div class="bg-white rounded-2xl border border-gray-300 shadow-md shadow-gray-900/5 p-5 sm:p-6 mb-6">
            <div class="flex justify-between items-center text-sm mb-2">
                <span class="font-medium text-gray-700">Verification progress</span>
                <span id="kyc-progress-label" class="text-gray-500">0% complete</span>
            </div>
            <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                <div id="kyc-progress-bar" class="h-full bg-orange-600 rounded-full transition-all" style="width: 0%"></div>
            </div>
        </div>

        <p id="kyc-verified-banner" class="hidden mb-6 flex items-center gap-2.5 text-sm text-green-700 bg-green-50 border border-green-200 rounded-2xl p-4">
            <svg width="18" height="18" class="h-[18px] w-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            You're fully verified. You can post listings, chat, and call vendors/buyers.
        </p>

        {{-- ── Step: Email ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-300 shadow-md shadow-gray-900/5 p-6 mb-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold shrink-0">1</span>
                    <h2 class="font-semibold text-gray-900">Verify email</h2>
                </div>
                <span id="kyc-email-badge" class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">Not verified</span>
            </div>
            <div id="kyc-email-form" class="space-y-3 pl-11">
                <button id="kyc-email-send" type="button" class="inline-flex items-center gap-2 text-sm font-semibold bg-orange-600 text-white px-4 py-2.5 rounded-lg shadow-sm hover:bg-orange-700 transition-colors">
                    <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    Send code to my email
                </button>
                <div id="kyc-email-verify-box" class="hidden flex flex-wrap gap-2 mt-2">
                    <input id="kyc-email-otp" type="text" inputmode="numeric" maxlength="6" placeholder="6-digit code" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors w-36">
                    <button id="kyc-email-verify" type="button" class="text-sm font-semibold bg-gray-900 text-white px-4 py-2.5 rounded-lg shadow-sm hover:bg-gray-800 transition-colors">Verify</button>
                </div>
                <p id="kyc-email-error" class="hidden text-xs text-red-600"></p>
            </div>
        </div>

        {{-- ── Step: Phone ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-300 shadow-md shadow-gray-900/5 p-6 mb-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold shrink-0">2</span>
                    <h2 class="font-semibold text-gray-900">Verify phone</h2>
                </div>
                <span id="kyc-phone-badge" class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">Not verified</span>
            </div>
            <div id="kyc-phone-form" class="space-y-3 pl-11">
                <button id="kyc-phone-send" type="button" class="inline-flex items-center gap-2 text-sm font-semibold bg-orange-600 text-white px-4 py-2.5 rounded-lg shadow-sm hover:bg-orange-700 transition-colors">
                    <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                    </svg>
                    Send code via SMS
                </button>
                <div id="kyc-phone-verify-box" class="hidden flex flex-wrap gap-2 mt-2">
                    <input id="kyc-phone-otp" type="text" inputmode="numeric" maxlength="6" placeholder="6-digit code" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors w-36">
                    <button id="kyc-phone-verify" type="button" class="text-sm font-semibold bg-gray-900 text-white px-4 py-2.5 rounded-lg shadow-sm hover:bg-gray-800 transition-colors">Verify</button>
                </div>
                <p id="kyc-phone-error" class="hidden text-xs text-red-600"></p>
            </div>
        </div>

        {{-- ── Step: Identity ──────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-300 shadow-md shadow-gray-900/5 p-6 mb-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold shrink-0">3</span>
                    <h2 class="font-semibold text-gray-900">Verify identity</h2>
                </div>
                <span id="kyc-identity-badge" class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">Not verified</span>
            </div>
            <div id="kyc-identity-form" class="space-y-3 pl-11">
                <select id="kyc-identity-type" class="w-full sm:w-auto rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                    <option value="nin">National ID (NIN)</option>
                    <option value="bvn">Bank Verification Number (BVN)</option>
                    <option value="drivers_license">Driver's license</option>
                    <option value="passport">International passport</option>
                </select>

                <div data-identity-fields="nin" class="flex gap-2">
                    <input type="text" data-field="nin" inputmode="numeric" maxlength="11" placeholder="11-digit NIN" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors flex-1">
                </div>
                <div data-identity-fields="bvn" class="hidden flex gap-2">
                    <input type="text" data-field="bvn" inputmode="numeric" maxlength="11" placeholder="11-digit BVN" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors flex-1">
                </div>
                <div data-identity-fields="drivers_license" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <input type="text" data-field="license_number" placeholder="License number" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                    <input type="date" data-field="date_of_birth" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                </div>
                <div data-identity-fields="passport" class="hidden grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <input type="text" data-field="passport_number" placeholder="Passport number" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                    <input type="text" data-field="last_name" placeholder="Last name" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                    <input type="date" data-field="date_of_birth" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                </div>

                <button id="kyc-identity-submit" type="button" class="inline-flex items-center gap-2 text-sm font-semibold bg-orange-600 text-white px-4 py-2.5 rounded-lg shadow-sm hover:bg-orange-700 transition-colors">
                    <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15 9.75m-3-7.036A11.955 11.955 0 013.598 6c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622a11.955 11.955 0 01-8.402-3.036 12.06 12.06 0 01-.599-.396c-.19-.129-.409-.129-.599 0z"/>
                    </svg>
                    Verify identity
                </button>
                <p id="kyc-identity-error" class="hidden text-xs text-red-600"></p>
            </div>
        </div>

        {{-- ── Optional: Business / CAC ────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-300 shadow-md shadow-gray-900/5 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center shrink-0">
                        <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                        </svg>
                    </span>
                    <h2 class="font-semibold text-gray-900">Business verification <span class="text-gray-400 font-normal text-sm block sm:inline">(optional — adds a verified-business badge)</span></h2>
                </div>
                <span id="kyc-cac-badge" class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full shrink-0 ml-2">Not verified</span>
            </div>
            <div class="flex flex-wrap gap-2 pl-11">
                <input id="kyc-cac-number" type="text" maxlength="20" placeholder="CAC registration number" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors flex-1 min-w-[200px]">
                <button id="kyc-cac-submit" type="button" class="text-sm font-semibold bg-gray-900 text-white px-4 py-2.5 rounded-lg shadow-sm hover:bg-gray-800 transition-colors">Verify</button>
            </div>
            <p id="kyc-cac-error" class="hidden text-xs text-red-600 mt-2 pl-11"></p>
            <p id="kyc-cac-success" class="hidden text-xs text-green-600 mt-2 pl-11"></p>
        </div>

        <a href="/post-ad" class="inline-flex items-center gap-1.5 text-sm font-semibold text-orange-600 hover:text-orange-700 transition-colors">
            <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Back to Post an Ad
        </a>
    </div>

</x-site-layout>
