<x-site-layout title="Settings — Sbrai Solutions" page="settings">

    <div id="settings-page" class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-1" data-i18n="settings">Settings</h1>
        <p class="text-sm text-gray-500 mb-6">Manage what you're notified about and what other users can see.</p>

        <p id="settings-error" class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl p-3 mb-4"></p>
        <p id="settings-success" class="hidden text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl p-3 mb-4"></p>

        <form id="settings-form" class="space-y-8">

            <section>
                <h2 class="font-semibold text-gray-900 mb-3">Notifications</h2>
                <div class="space-y-3">

                    <div class="flex items-center justify-between gap-4 border-2 border-gray-300 rounded-xl p-4 bg-white">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-9 h-9 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                <svg width="18" height="18" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-gray-700 truncate">New listings matching your interests</span>
                        </div>
                        <label class="cursor-pointer shrink-0">
                            <input type="checkbox" name="notifications.new_listings" class="sr-only peer">
                            <div class="w-11 h-6 rounded-full bg-gray-300 peer-checked:bg-orange-600 flex items-center justify-start peer-checked:justify-end p-0.5 transition-colors peer-focus:ring-4 peer-focus:ring-orange-200">
                                <span class="h-5 w-5 rounded-full bg-white shadow"></span>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between gap-4 border-2 border-gray-300 rounded-xl p-4 bg-white">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-9 h-9 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                <svg width="18" height="18" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.831.699 2.53 0l7.409-7.409c.699-.699.699-1.83 0-2.53L13.16 3.66A2.25 2.25 0 0011.568 3z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-gray-700 truncate">Price drops on favourited listings</span>
                        </div>
                        <label class="cursor-pointer shrink-0">
                            <input type="checkbox" name="notifications.price_drops" class="sr-only peer">
                            <div class="w-11 h-6 rounded-full bg-gray-300 peer-checked:bg-orange-600 flex items-center justify-start peer-checked:justify-end p-0.5 transition-colors peer-focus:ring-4 peer-focus:ring-orange-200">
                                <span class="h-5 w-5 rounded-full bg-white shadow"></span>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between gap-4 border-2 border-gray-300 rounded-xl p-4 bg-white">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-9 h-9 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                <svg width="18" height="18" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.8L3 20l1.3-3.9C3.5 15 3 13.6 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-gray-700 truncate">New messages</span>
                        </div>
                        <label class="cursor-pointer shrink-0">
                            <input type="checkbox" name="notifications.messages" class="sr-only peer">
                            <div class="w-11 h-6 rounded-full bg-gray-300 peer-checked:bg-orange-600 flex items-center justify-start peer-checked:justify-end p-0.5 transition-colors peer-focus:ring-4 peer-focus:ring-orange-200">
                                <span class="h-5 w-5 rounded-full bg-white shadow"></span>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between gap-4 border-2 border-gray-300 rounded-xl p-4 bg-white">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-9 h-9 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                <svg width="18" height="18" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 01-4.5-4.5V7.5a4.5 4.5 0 014.5-4.5h9a4.5 4.5 0 014.5 4.5v3.75a4.5 4.5 0 01-4.5 4.5h-.75c-.704 0-1.402.03-2.09.09-.404.036-.802.089-1.194.16a15.11 15.11 0 01-2.44 0 15.166 15.166 0 01-1.192-.16z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21l1.5-3M15.75 21l-1.5-3"/>
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-gray-700 truncate">Promotions and news from Sbrai</span>
                        </div>
                        <label class="cursor-pointer shrink-0">
                            <input type="checkbox" name="notifications.promotions" class="sr-only peer">
                            <div class="w-11 h-6 rounded-full bg-gray-300 peer-checked:bg-orange-600 flex items-center justify-start peer-checked:justify-end p-0.5 transition-colors peer-focus:ring-4 peer-focus:ring-orange-200">
                                <span class="h-5 w-5 rounded-full bg-white shadow"></span>
                            </div>
                        </label>
                    </div>

                </div>
            </section>

            <section>
                <h2 class="font-semibold text-gray-900 mb-3">Privacy</h2>
                <div class="space-y-3">

                    <div class="flex items-center justify-between gap-4 border-2 border-gray-300 rounded-xl p-4 bg-white">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-9 h-9 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                <svg width="18" height="18" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-gray-700 truncate">Show my phone number to vendors/buyers I chat with</span>
                        </div>
                        <label class="cursor-pointer shrink-0">
                            <input type="checkbox" name="privacy.show_phone" class="sr-only peer">
                            <div class="w-11 h-6 rounded-full bg-gray-300 peer-checked:bg-orange-600 flex items-center justify-start peer-checked:justify-end p-0.5 transition-colors peer-focus:ring-4 peer-focus:ring-orange-200">
                                <span class="h-5 w-5 rounded-full bg-white shadow"></span>
                            </div>
                        </label>
                    </div>

                </div>

                <a href="https://sbraisolutions.com/privacy-policy" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-orange-600 hover:text-orange-700 hover:underline">
                    <svg width="15" height="15" class="h-[15px] w-[15px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15 9.75m-3-7.036A11.955 11.955 0 013.598 6c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622a11.955 11.955 0 01-8.402-3.036 12.06 12.06 0 01-.599-.396c-.19-.129-.409-.129-.599 0z"/>
                    </svg>
                    Read our Privacy Policy
                </a>
            </section>

            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm transition">
                Save settings
            </button>
        </form>

        <div class="mt-10 pt-6 border-t border-gray-200">
            <h2 class="font-semibold text-gray-900 mb-1">Danger zone</h2>
            <p class="text-sm text-gray-500 mb-3">Permanently delete your account and all associated data. This action cannot be undone.</p>
            <button type="button" id="delete-account-btn" class="inline-flex items-center gap-2 border-2 border-red-200 text-red-600 hover:bg-red-50 font-semibold px-5 py-2.5 rounded-xl transition">
                <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                </svg>
                Delete account
            </button>
        </div>
    </div>

</x-site-layout>
