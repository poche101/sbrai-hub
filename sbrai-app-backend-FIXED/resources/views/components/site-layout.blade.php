<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sbrai Solutions' }}</title>
    <meta name="description" content="Verified building-materials, artisan-services and property marketplace for Nigeria.">
    <style>
        /* [data-nav="user"]/[data-nav="guest"] have no `flex` class of their own —
           site.js only toggles the `hidden` class, so without this, removing
           `hidden` falls back to block display and the row's children stack
           vertically instead of sitting inline. */
        [data-nav="guest"],
        [data-nav="user"] {
            display: flex;
        }
        [data-nav="guest"].hidden,
        [data-nav="user"].hidden {
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/site.js'])
</head>
<body data-page="{{ $page ?? '' }}" class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center gap-6">
            <!-- Logo & Brand Name -->
            <a href="/" class="flex items-center gap-2.5 shrink-0">
                <img src="/images/app.jpeg" alt="Sbrai Solutions Logo" class="w-9 h-9 object-cover rounded-lg">
                <span class="text-lg font-bold text-gray-900 tracking-tight">Sbrai Solutions</span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 text-sm font-medium text-gray-600">
                <a href="/browse" class="px-3 py-2 rounded-lg hover:bg-gray-50 hover:text-orange-600 transition">Browse</a>
                <a href="/post-ad" data-nav="post-ad-link" class="px-3 py-2 rounded-lg hover:bg-gray-50 hover:text-orange-600 transition">Post an ad</a>
            </nav>

            <!-- Authentication / User Actions -->
            <div class="flex items-center gap-2 ml-auto">
                <div class="hidden lg:flex items-center gap-1.5 pl-2.5 pr-1.5 py-1.5 rounded-lg hover:bg-gray-50 transition">
                    <i class="ti ti-world text-[15px] text-gray-400" aria-hidden="true"></i>
                    <span class="sr-only">Language</span>
                    <select id="language-select" class="bg-transparent text-sm text-gray-700 outline-none cursor-pointer pr-1">
                        <option value="en">English</option>
                        <option value="yo">Yorùbá</option>
                        <option value="ig">Igbo</option>
                        <option value="ha">Hausa</option>
                        <option value="fr">Français</option>
                    </select>
                </div>

                <div data-nav="guest" class="flex items-center gap-2">
                    <a href="/auth" class="text-sm font-medium text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition">Log in</a>
                    <a href="/auth?mode=register" class="text-sm font-semibold bg-orange-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-orange-700 transition">Sign up</a>
                </div>

                <div data-nav="user" class="hidden items-center gap-2 sm:gap-3">
                    <details id="notif-dropdown" class="relative">
                        <summary class="list-none relative w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-700 cursor-pointer transition" aria-label="Notifications">
                            <i class="ti ti-bell text-[19px]" aria-hidden="true"></i>
                            <span id="notif-badge" class="hidden absolute -top-1 -right-1 min-w-[16px] h-4 px-1 rounded-full bg-orange-600 text-white text-[10px] font-semibold flex items-center justify-center leading-none ring-2 ring-white">0</span>
                        </summary>
                        <div id="notif-panel" class="absolute right-0 mt-2 w-80 max-h-96 overflow-y-auto rounded-xl border border-gray-100 bg-white shadow-lg shadow-gray-200/60 text-sm">
                            <div class="p-3.5 border-b border-gray-100 flex items-center justify-between">
                                <span class="font-semibold text-gray-900" data-i18n="notifications">Notifications</span>
                                <button type="button" id="notif-mark-all" class="text-xs font-medium text-orange-600 hover:text-orange-700">Mark all read</button>
                            </div>
                            <div id="notif-list" class="divide-y divide-gray-100">
                                <p class="p-6 text-center text-gray-400 text-xs">Loading…</p>
                            </div>
                        </div>
                    </details>

                    <details class="relative">
                        <summary class="list-none flex items-center gap-2 pl-1.5 pr-2.5 py-1.5 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <span data-nav="user-avatar" class="w-7 h-7 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold flex items-center justify-center shrink-0">?</span>
                            <span data-nav="user-name" class="hidden sm:inline text-sm font-medium text-gray-700">Account</span>
                            <i class="ti ti-chevron-down text-[15px] text-gray-400" aria-hidden="true"></i>
                        </summary>
                        <div class="absolute right-0 mt-2 w-60 rounded-xl border border-gray-100 bg-white shadow-lg shadow-gray-200/60 py-1.5 text-sm">
                            <div class="px-3.5 py-2.5 border-b border-gray-100 mb-1">
                                <p data-nav="user-email" class="text-xs text-gray-400 truncate"></p>
                            </div>
                            <a href="/favourites" data-i18n="myFavorites" class="flex items-center gap-2.5 px-3.5 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                                <i class="ti ti-heart text-[17px] text-gray-400" aria-hidden="true"></i>
                                Favourites
                            </a>
                            <a href="/messages" data-i18n="messages" class="flex items-center gap-2.5 px-3.5 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                                <i class="ti ti-message-circle text-[17px] text-gray-400" aria-hidden="true"></i>
                                Messages
                            </a>
                            <a href="/profile" data-i18n="myProfile" class="flex items-center gap-2.5 px-3.5 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                                <i class="ti ti-user text-[17px] text-gray-400" aria-hidden="true"></i>
                                Profile
                            </a>
                            <a href="/kyc" data-i18n="verification" class="flex items-center gap-2.5 px-3.5 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                                <i class="ti ti-shield-check text-[17px] text-gray-400" aria-hidden="true"></i>
                                Verification
                            </a>
                            <a href="/settings" data-i18n="settings" class="flex items-center gap-2.5 px-3.5 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                                <i class="ti ti-settings text-[17px] text-gray-400" aria-hidden="true"></i>
                                Settings
                            </a>
                            <hr class="border-gray-100 my-1.5">
                            <a href="#" data-action="logout" data-i18n="signOut" class="flex items-center gap-2.5 px-3.5 py-2.5 text-red-600 hover:bg-red-50 transition">
                                <i class="ti ti-logout text-[17px]" aria-hidden="true"></i>
                                Sign out
                            </a>
                        </div>
                    </details>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Bar -->
        <nav class="md:hidden flex items-center justify-center gap-6 px-4 py-3 bg-gray-50 border-t border-gray-100 text-sm font-semibold text-gray-700">
            <a href="/browse" class="hover:text-orange-600 transition">Browse</a>
            <a href="/post-ad" data-nav="post-ad-link" class="hover:text-orange-600 transition">Post an ad</a>
        </nav>
    </header>

    <main class="flex-1">
        @if ($showSidebar ?? false)
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex flex-col sm:flex-row gap-6 items-start">
                <x-category-sidebar :categories="$categories ?? null" :active="$activeCategory ?? null" />

                <div class="flex-1 min-w-0">
                    {{ $slot }}
                </div>
            </div>
        @else
            {{ $slot }}
        @endif
    </main>

    <footer class="bg-white border-t border-gray-200 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">

        <div class="flex flex-col sm:flex-row sm:justify-between gap-10 sm:gap-8">

            {{-- Brand column --}}
            <div class="sm:w-72 sm:shrink-0">
                <div class="flex items-center gap-2.5 mb-3">
                    <img src="/images/app.jpeg" alt="Sbrai Solutions Logo" class="w-9 h-9 object-cover rounded-xl ring-1 ring-gray-100">
                    <span class="text-lg font-extrabold text-gray-900 tracking-tight">
                        Sbrai <span class="text-orange-600">Solutions</span>
                    </span>
                </div>
                <p class="text-sm text-gray-500 leading-relaxed mb-4">
                    Verified marketplace for building materials, artisan services and property across Nigeria.
                </p>
                <div class="flex items-center gap-2">
                    <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-lg bg-gray-50 hover:bg-orange-50 hover:text-orange-600 text-gray-500 flex items-center justify-center transition-colors">
                        <svg width="16" height="16" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12.06C22 6.505 17.523 2 12 2S2 6.505 2 12.06c0 5.02 3.657 9.184 8.438 9.94v-7.03H7.898v-2.91h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.876h2.773l-.443 2.91h-2.33V22c4.78-.756 8.437-4.92 8.437-9.94z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-lg bg-gray-50 hover:bg-orange-50 hover:text-orange-600 text-gray-500 flex items-center justify-center transition-colors">
                        <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <rect x="3" y="3" width="18" height="18" rx="5"/>
                            <circle cx="12" cy="12" r="4"/>
                            <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="X (Twitter)" class="w-9 h-9 rounded-lg bg-gray-50 hover:bg-orange-50 hover:text-orange-600 text-gray-500 flex items-center justify-center transition-colors">
                        <svg width="16" height="16" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="WhatsApp" class="w-9 h-9 rounded-lg bg-gray-50 hover:bg-orange-50 hover:text-orange-600 text-gray-500 flex items-center justify-center transition-colors">
                        <svg width="16" height="16" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.29-1.39a9.9 9.9 0 004.7 1.2h.01c5.46 0 9.9-4.45 9.9-9.9C21.96 6.45 17.5 2 12.04 2zm5.83 14.08c-.24.68-1.4 1.33-1.93 1.4-.5.08-1.11.11-1.79-.11-.41-.13-.94-.3-1.62-.6-2.84-1.23-4.7-4.1-4.84-4.29-.14-.19-1.16-1.54-1.16-2.94s.73-2.09.99-2.37c.26-.29.56-.36.75-.36.19 0 .38 0 .54.01.18.01.41-.07.64.49.24.58.81 2 .88 2.14.07.15.11.32.02.51-.09.19-.14.31-.28.48-.14.17-.29.37-.42.5-.14.14-.28.29-.13.57.15.28.68 1.13 1.47 1.83 1.01.9 1.87 1.19 2.15 1.32.28.13.44.11.6-.07.17-.18.71-.83.9-1.11.19-.29.38-.24.63-.15.26.1 1.65.78 1.94.92.28.14.47.21.53.33.07.12.07.68-.17 1.35z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Link groups --}}
            <div class="flex flex-wrap gap-10 sm:gap-16">

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Marketplace</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/browse" class="text-gray-500 hover:text-orange-600 transition-colors">Browse listings</a></li>
                        <li><a href="/post-ad" class="text-gray-500 hover:text-orange-600 transition-colors">Sell on Sbrai</a></li>
                        <li><a href="/pricing" class="text-gray-500 hover:text-orange-600 transition-colors">Pricing</a></li>
                        <li><a href="/kyc" class="text-gray-500 hover:text-orange-600 transition-colors">Vendor verification</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Support</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="text-gray-500 hover:text-orange-600 transition-colors">Help centre</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-orange-600 transition-colors">Contact us</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-orange-600 transition-colors">Safety tips</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-orange-600 transition-colors">Report a listing</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Company</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="text-gray-500 hover:text-orange-600 transition-colors">About us</a></li>
                        <li><a href="https://sbraisolutions.com/privacy-policy" target="_blank" rel="noopener" class="text-gray-500 hover:text-orange-600 transition-colors">Privacy policy</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-orange-600 transition-colors">Terms of service</a></li>
                    </ul>
                </div>

            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Sbrai Solutions. All rights reserved.</p>
            <p class="text-xs text-gray-400">Made for verified building materials, services &amp; property trade in Nigeria.</p>
        </div>

    </div>
</footer>
</body>
</html>
