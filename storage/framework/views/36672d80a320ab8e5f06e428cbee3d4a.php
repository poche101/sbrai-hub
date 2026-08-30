<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title ?? 'Sbrai Solutions'); ?></title>
    <meta name="description" content="Verified building-materials, artisan-services and property marketplace for Nigeria.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js', 'resources/js/site.js']); ?>

    <style>
        /* ── Modern dropdown panels (notifications + account menu) ─── */
        @keyframes dropdown-in {
            from { opacity: 0; transform: translateY(-6px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        details[open] > summary + * {
            animation: dropdown-in 0.18s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        details[open] .dropdown-chevron {
            transform: rotate(180deg);
        }
        .dropdown-chevron {
            transition: transform 0.2s ease;
        }
        details > summary {
            outline: none;
        }
        details > summary::-webkit-details-marker {
            display: none;
        }

        .menu-item {
            transition: background-color 0.15s ease, color 0.15s ease, padding-left 0.15s ease;
        }
        .menu-item:hover {
            padding-left: 1.125rem;
        }
        .menu-item-icon {
            transition: color 0.15s ease, transform 0.15s ease;
        }
        .menu-item:hover .menu-item-icon {
            transform: scale(1.08);
        }

        #notif-badge {
            animation: dropdown-in 0.25s ease both;
        }

        /* ── Footer ───────────────────────────────────────────────── */
        .footer-link {
            transition: color 0.15s ease, transform 0.15s ease;
            display: inline-block;
        }
        .footer-link:hover {
            color: #ea580c;
            transform: translateX(2px);
        }
        .footer-social-btn {
            transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .footer-social-btn:hover {
            background-color: #ea580c;
            color: #fff;
            transform: translateY(-2px);
        }
        .footer-newsletter-btn {
            transition: background-color 0.2s ease, transform 0.15s ease;
        }
        .footer-newsletter-btn:hover {
            transform: translateY(-1px);
        }
        .footer-badge {
            transition: border-color 0.2s ease, color 0.2s ease;
        }
        .footer-badge:hover {
            border-color: #d1d5db;
            color: #4b5563;
        }

        /* ── Footer column layout (flex-based, replaces the old grid) ──
             .footer-flex spreads the columns across the row using flex
             instead of CSS grid. .footer-flex-brand gets more width
             since it holds the logo + description + socials. Make sure
             the wrapping <div> below has class="footer-flex" and the
             brand block has class="footer-flex-brand". */
        .footer-flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 2rem 1.5rem;
        }
        .footer-flex > div {
            flex: 1 1 140px;
        }
        .footer-flex-brand {
            flex: 1 1 260px;
            max-width: 340px;
        }

        /* ── Mobile hamburger menu ────────────────────────────────── */
        .hamburger-line {
            transition: transform 0.25s cubic-bezier(0.65, 0, 0.35, 1), opacity 0.2s ease;
            transform-origin: center;
        }
        #mobile-menu-btn.is-open .hamburger-line-top {
            transform: translateY(6px) rotate(45deg);
        }
        #mobile-menu-btn.is-open .hamburger-line-mid {
            opacity: 0;
        }
        #mobile-menu-btn.is-open .hamburger-line-bottom {
            transform: translateY(-6px) rotate(-45deg);
        }
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #mobile-menu.is-open {
            max-height: 32rem;
        }

        /* ── Sidebar + content two-column layout ──────────────────── */
        .sidebar-layout {
            display: block;
        }
        @media (min-width: 640px) {
            .sidebar-layout {
                display: grid;
                grid-template-columns: 14rem minmax(0, 1fr);
                gap: 1.5rem;
                align-items: start;
            }
        }

        /* ── Desktop auth cluster wrapper ─────────────────────────────
             IMPORTANT: this breakpoint control lives on a WRAPPER, not
             on the elements site.js toggles (data-nav="guest"/"user").
             Putting a responsive utility directly on those caused a
             real bug: Tailwind's sm:flex was overriding the JS-added
             "hidden" class after login, showing both guest AND account
             UI at once. Keeping the two concerns on separate elements
             avoids that conflict entirely. */
        .desktop-auth-cluster {
            display: none;
        }
        @media (min-width: 768px) {
            .desktop-auth-cluster {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body data-page="<?php echo e($page ?? ''); ?>" class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center gap-6">
            <!-- Logo & Brand Name -->
            <a href="/" class="flex items-center gap-2.5 shrink-0">
                <img src="/images/app.jpeg" alt="Sbrai Solutions Logo" class="w-9 h-9 object-cover rounded-lg">
                <span class="text-lg font-bold text-gray-900 tracking-tight" data-no-translate>Sbrai Solutions</span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 text-sm font-medium text-gray-600">
                <a href="/browse" class="px-3 py-2 rounded-lg hover:bg-gray-50 hover:text-orange-600 transition">Browse</a>
                <a href="/post-ad" data-nav="post-ad-link" data-role-visibility="vendor" class="hidden px-3 py-2 rounded-lg hover:bg-gray-50 hover:text-orange-600 transition">Post an ad</a>
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

                
                <div class="desktop-auth-cluster">
                    
                    <div data-nav="guest" class="items-center gap-2">
                        <a href="/auth" class="text-sm font-medium text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition">Log in</a>
                        <a href="/auth?mode=register" class="text-sm font-semibold bg-orange-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-orange-700 transition">Sign up</a>
                    </div>

                    
                    <div data-nav="user" class="hidden items-center gap-4">
                        <details id="notif-dropdown" class="relative mr-1">
                            <summary class="list-none relative w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-700 cursor-pointer transition" aria-label="Notifications">
                                <i class="ti ti-bell text-[19px]" aria-hidden="true"></i>
                                <span id="notif-badge" class="hidden absolute top-1 right-1 min-w-[16px] h-4 px-1 rounded-full bg-orange-600 text-white text-[10px] font-semibold flex items-center justify-center leading-none ring-2 ring-white">0</span>
                            </summary>
                            <div id="notif-panel" class="absolute right-0 mt-2.5 w-80 max-h-96 overflow-y-auto rounded-2xl border border-gray-100 bg-white shadow-xl shadow-gray-300/30 text-sm ring-1 ring-black/5">
                                <div class="p-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur rounded-t-2xl">
                                    <span class="font-bold text-gray-900" data-i18n="notifications">Notifications</span>
                                    <button type="button" id="notif-mark-all" class="text-xs font-semibold text-orange-600 hover:text-orange-700 hover:underline">Mark all read</button>
                                </div>
                                <div id="notif-list" class="divide-y divide-gray-100">
                                    <p class="p-8 text-center text-gray-400 text-xs">Loading…</p>
                                </div>
                            </div>
                        </details>

                        <details class="relative">
                            <summary class="list-none flex items-center gap-2 pl-1.5 pr-2.5 py-1.5 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <span data-nav="user-avatar" class="w-7 h-7 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold flex items-center justify-center shrink-0 ring-2 ring-white shadow-sm">?</span>
                                <span data-nav="user-name" class="hidden sm:inline text-sm font-medium text-gray-700">Account</span>
                                <i class="ti ti-chevron-down dropdown-chevron text-[15px] text-gray-400" aria-hidden="true"></i>
                            </summary>
                            <div class="absolute right-0 mt-2.5 w-64 rounded-2xl border border-gray-100 bg-white shadow-xl shadow-gray-300/30 py-2 text-sm ring-1 ring-black/5">
                                <div class="px-4 py-3 border-b border-gray-100 mb-1.5 flex items-center gap-3">
                                    <span data-nav="user-avatar-lg" class="w-10 h-10 rounded-full bg-orange-100 text-orange-700 text-sm font-bold flex items-center justify-center shrink-0">?</span>
                                    <div class="min-w-0">
                                        <p data-nav="user-name-lg" class="text-sm font-semibold text-gray-900 truncate">Account</p>
                                        <p data-nav="user-email" class="text-xs text-gray-400 truncate"></p>
                                    </div>
                                </div>
                                <a href="/favourites" data-i18n="myFavorites" class="menu-item flex items-center gap-2.5 px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg mx-1.5 transition">
                                    <i class="ti ti-heart menu-item-icon text-[17px] text-gray-400" aria-hidden="true"></i>
                                    Favourites
                                </a>
                                <a href="/messages" data-i18n="messages" class="menu-item flex items-center gap-2.5 px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg mx-1.5 transition">
                                    <i class="ti ti-message-circle menu-item-icon text-[17px] text-gray-400" aria-hidden="true"></i>
                                    Messages
                                </a>
                                <a href="/profile" data-i18n="myProfile" class="menu-item flex items-center gap-2.5 px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg mx-1.5 transition">
                                    <i class="ti ti-user menu-item-icon text-[17px] text-gray-400" aria-hidden="true"></i>
                                    Profile
                                </a>
                                <a href="/kyc" data-i18n="verification" class="menu-item flex items-center gap-2.5 px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg mx-1.5 transition">
                                    <i class="ti ti-shield-check menu-item-icon text-[17px] text-gray-400" aria-hidden="true"></i>
                                    Verification
                                </a>
                                <a href="/settings" data-i18n="settings" class="menu-item flex items-center gap-2.5 px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg mx-1.5 transition">
                                    <i class="ti ti-settings menu-item-icon text-[17px] text-gray-400" aria-hidden="true"></i>
                                    Settings
                                </a>
                                <hr class="border-gray-100 my-1.5">
                                <a href="#" data-action="logout" data-i18n="signOut" class="menu-item flex items-center gap-2.5 px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-lg mx-1.5 transition">
                                    <i class="ti ti-logout menu-item-icon text-[17px]" aria-hidden="true"></i>
                                    Sign out
                                </a>
                            </div>
                        </details>
                    </div>
                </div>

                <!-- Hamburger toggle — visible below md -->
                <button id="mobile-menu-btn" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu"
                        class="md:hidden w-9 h-9 rounded-lg flex items-center justify-center text-gray-600 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line class="hamburger-line hamburger-line-top" x1="3" y1="6" x2="21" y2="6"/>
                        <line class="hamburger-line hamburger-line-mid" x1="3" y1="12" x2="21" y2="12"/>
                        <line class="hamburger-line hamburger-line-bottom" x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile slide-down menu -->
        <div id="mobile-menu" class="md:hidden border-t border-gray-100 bg-white">
            <div class="px-4 sm:px-6 py-4 space-y-1">
                <a href="/browse" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Browse</a>
                <a href="/post-ad" data-nav="post-ad-link-mobile" data-role-visibility="vendor" class="hidden px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Post an ad</a>

                <div data-nav="user-mobile" class="hidden">
                    <hr class="border-gray-100 my-2">
                    <a href="/favourites" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                        <i class="ti ti-heart text-[17px] text-gray-400" aria-hidden="true"></i> Favourites
                    </a>
                    <a href="/messages" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                        <i class="ti ti-message-circle text-[17px] text-gray-400" aria-hidden="true"></i> Messages
                    </a>
                    <a href="/profile" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                        <i class="ti ti-user text-[17px] text-gray-400" aria-hidden="true"></i> Profile
                    </a>
                    <a href="/kyc" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                        <i class="ti ti-shield-check text-[17px] text-gray-400" aria-hidden="true"></i> Verification
                    </a>
                    <a href="/settings" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                        <i class="ti ti-settings text-[17px] text-gray-400" aria-hidden="true"></i> Settings
                    </a>
                    <a href="#" data-action="logout" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-red-600 hover:bg-red-50">
                        <i class="ti ti-logout text-[17px]" aria-hidden="true"></i> Sign out
                    </a>
                </div>

                <div data-nav="guest-mobile" class="hidden pt-2 flex flex-col gap-2">
                    <a href="/auth" class="text-center text-sm font-medium text-gray-700 px-3 py-2.5 rounded-lg border border-gray-200">Log in</a>
                    <a href="/auth?mode=register" class="text-center text-sm font-semibold bg-orange-600 text-white px-3 py-2.5 rounded-lg shadow-sm">Sign up</a>
                </div>

                <div class="pt-2 flex items-center gap-1.5 px-3">
                    <i class="ti ti-world text-[15px] text-gray-400" aria-hidden="true"></i>
                    <select id="language-select-mobile" class="bg-transparent text-sm text-gray-700 outline-none cursor-pointer">
                        <option value="en">English</option>
                        <option value="yo">Yorùbá</option>
                        <option value="ig">Igbo</option>
                        <option value="ha">Hausa</option>
                        <option value="fr">Français</option>
                    </select>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1">
        <?php if($showSidebar ?? false): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
                <div class="sidebar-layout">
                    <?php if (isset($component)) { $__componentOriginal26867321cda7cfb9e950dcf38497df9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26867321cda7cfb9e950dcf38497df9c = $attributes; } ?>
<?php $component = App\View\Components\CategorySidebar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('category-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\CategorySidebar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories ?? null),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeCategory ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26867321cda7cfb9e950dcf38497df9c)): ?>
<?php $attributes = $__attributesOriginal26867321cda7cfb9e950dcf38497df9c; ?>
<?php unset($__attributesOriginal26867321cda7cfb9e950dcf38497df9c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26867321cda7cfb9e950dcf38497df9c)): ?>
<?php $component = $__componentOriginal26867321cda7cfb9e950dcf38497df9c; ?>
<?php unset($__componentOriginal26867321cda7cfb9e950dcf38497df9c); ?>
<?php endif; ?>
                    <div class="min-w-0">
                        <?php echo e($slot); ?>

                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php echo e($slot); ?>

        <?php endif; ?>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-16">

        
        <div class="border-b border-gray-100 bg-gray-50/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-center md:text-left">
                    <p class="text-base font-bold text-gray-900">Stay in the loop</p>
                    <p class="text-sm text-gray-500 mt-0.5">Get new listings and marketplace tips in your inbox.</p>
                </div>
               <form class="w-full md:w-auto flex flex-col sm:flex-row gap-1">
    <input type="email" placeholder="Enter your email"
           class="w-full sm:w-64 sm:flex-none rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition">
    <button type="submit" class="footer-newsletter-btn shrink-0 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm">
        Subscribe
    </button>
</form>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 footer-flex">

            <div class="footer-flex-brand pr-4">
                <a href="/" class="flex items-center gap-2.5 mb-3">
                    <img src="/images/app.jpeg" alt="Sbrai Solutions Logo" class="w-9 h-9 object-cover rounded-lg">
                    <span class="text-lg font-bold text-gray-900 tracking-tight">Sbrai Solutions</span>
                </a>
                <p class="text-sm text-gray-500 leading-relaxed mb-4 max-w-xs">
                    Nigeria's verified marketplace for building materials, artisan services, and property — connecting trusted buyers and vendors nationwide.
                </p>
                <div class="flex items-center gap-2">
                    <a href="#" aria-label="Facebook" class="footer-social-btn w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center">
                        <i class="ti ti-brand-facebook text-[17px]" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="footer-social-btn w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center">
                        <i class="ti ti-brand-instagram text-[17px]" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="X (Twitter)" class="footer-social-btn w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center">
                        <i class="ti ti-brand-x text-[17px]" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="WhatsApp" class="footer-social-btn w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center">
                        <i class="ti ti-brand-whatsapp text-[17px]" aria-hidden="true"></i>
                    </a>
                    <a href="#" aria-label="LinkedIn" class="footer-social-btn w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center">
                        <i class="ti ti-brand-linkedin text-[17px]" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-900 mb-3">Marketplace</p>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="/browse" class="footer-link">Browse listings</a></li>
                    <li><a href="/browse?category=materials" class="footer-link">Building materials</a></li>
                    <li><a href="/browse?category=services" class="footer-link">Artisan services</a></li>
                    <li><a href="/browse?category=property" class="footer-link">Property</a></li>
                    <li><a href="/post-ad" class="footer-link">Sell on Sbrai</a></li>
                </ul>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-900 mb-3">Company</p>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="/about" class="footer-link">About us</a></li>
                    <li><a href="/blog" class="footer-link">Blog</a></li>
                    <li><a href="/pricing" class="footer-link">Vendor pricing</a></li>
                    <li><a href="#" class="footer-link">Contact us</a></li>
                </ul>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-900 mb-3">Support</p>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="/help" class="footer-link">Help center</a></li>
                    <li><a href="/kyc" class="footer-link">Verification / KYC</a></li>
                    <li><a href="/messages" class="footer-link">Messages</a></li>
                </ul>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-900 mb-3">Legal</p>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="#" class="footer-link">Terms &amp; conditions</a></li>
                    <li><a href="https://sbraisolutions.com/privacy-policy/" class="footer-link">Privacy policy</a></li>
                    <li><a href="#" class="footer-link">Refund policy</a></li>
                </ul>
            </div>
        </div>

        
        <div class="border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex flex-col-reverse md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-400 text-center md:text-left">
                    &copy; <?php echo e(date('Y')); ?> Sbrai Solutions. All rights reserved.
                </p>
                <div class="flex items-center gap-2.5">
                    <span class="footer-badge flex items-center gap-1.5 text-xs text-gray-400 border border-gray-200 rounded-lg px-2.5 py-1.5">
                        <i class="ti ti-shield-check text-[14px]" aria-hidden="true"></i>
                        KYC Verified Vendors
                    </span>
                    <span class="footer-badge flex items-center gap-1.5 text-xs text-gray-400 border border-gray-200 rounded-lg px-2.5 py-1.5">
                        <i class="ti ti-lock text-[14px]" aria-hidden="true"></i>
                        Secure Payments
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');

            btn?.addEventListener('click', () => {
                const isOpen = menu.classList.toggle('is-open');
                btn.classList.toggle('is-open', isOpen);
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            const guestMobile = document.querySelector('[data-nav="guest-mobile"]');
            const userMobile = document.querySelector('[data-nav="user-mobile"]');
            const guestDesktop = document.querySelector('[data-nav="guest"]');

            if (guestDesktop && guestMobile && userMobile) {
                const syncMobileAuthState = () => {
                    const loggedOut = !guestDesktop.classList.contains('hidden');
                    guestMobile.classList.toggle('hidden', !loggedOut);
                    userMobile.classList.toggle('hidden', loggedOut);
                };
                syncMobileAuthState();
                new MutationObserver(syncMobileAuthState).observe(guestDesktop, { attributes: true, attributeFilter: ['class'] });
            }

            const langDesktop = document.getElementById('language-select');
            const langMobile = document.getElementById('language-select-mobile');
            langMobile?.addEventListener('change', () => {
                if (langDesktop) {
                    langDesktop.value = langMobile.value;
                    langDesktop.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/components/site-layout.blade.php ENDPATH**/ ?>