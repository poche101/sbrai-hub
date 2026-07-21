<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} — Sbrai Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #E5E5E5;
            border-radius: 10px;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #E8622A, #C4501F);
            color: white;
        }

        .sidebar-link.active svg {
            color: white;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Sidebar: off-canvas on mobile, docked on lg+ */
        #sidebar {
            transform: translateX(-100%);
            transition: transform 0.25s ease-in-out;
        }

        #sidebar.sidebar-open {
            transform: translateX(0);
        }

        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0) !important;
            }
        }

        #sidebarOverlay {
            display: none;
        }

        #sidebarOverlay.overlay-visible {
            display: block;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orange: {
                            500: '#E8622A',
                            600: '#C4501F',
                            50: '#FFF0EB'
                        },
                        navy: {
                            500: '#1A2B4A',
                            600: '#0F1C33',
                            50: '#EEF2FF'
                        },
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-900">

    <div class="flex min-h-screen">

        {{-- Mobile overlay backdrop --}}
        <div id="sidebarOverlay" onclick="closeSidebar()" class="fixed inset-0 bg-black/40 z-40 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside id="sidebar"
            class="w-64 bg-white border-r border-gray-200 fixed h-full flex flex-col z-50 lg:translate-x-0">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-extrabold text-lg shadow-md flex-shrink-0">
                        S</div>
                    <div class="min-w-0">
                        <div class="font-bold text-sm text-gray-900 truncate">Sbrai Admin</div>
                        <div class="text-xs text-gray-400 truncate">Control Panel</div>
                    </div>
                </div>
                <button onclick="closeSidebar()" class="lg:hidden p-1 text-gray-400 hover:text-gray-600 flex-shrink-0" aria-label="Close menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-thin">
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Overview
                </a>
                <a href="{{ route('admin.kyc.index') }}"
                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition {{ request()->routeIs('admin.kyc*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="truncate">KYC Requests</span>
                    @if (($stats['pending_kyc'] ?? 0) > 0)
                        <span
                            class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full flex-shrink-0">{{ $stats['pending_kyc'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-6 0 4 4 0 016 0zm6 3a4 4 0 10-6 0" />
                    </svg>
                    Users
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Categories
                </a>
                <div class="pt-4 pb-1 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Reports</div>
                <a href="{{ route('admin.reports.revenue') }}"
                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition {{ request()->routeIs('admin.reports.revenue*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="truncate">Revenue Reports</span>
                </a>
                <a href="{{ route('admin.listings.index') }}"
                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition {{ request()->routeIs('admin.listings*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Listings
                </a>
            </nav>

            <div class="p-4 border-t border-gray-200">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition w-full">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 w-full lg:ml-64 min-w-0">
            <header
                class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between sticky top-0 z-30 gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Hamburger — mobile/tablet only --}}
                    <button onclick="openSidebar()" class="lg:hidden p-1.5 -ml-1.5 text-gray-500 hover:text-gray-800 flex-shrink-0" aria-label="Open menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <h1 class="text-base sm:text-lg font-bold text-gray-900 truncate">{{ $title ?? 'Dashboard' }}</h1>
                        <p class="text-xs text-gray-400 truncate hidden sm:block">{{ $subtitle ?? 'Manage Sbrai Hub' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:gap-4 flex-shrink-0">
                    <div class="relative hidden sm:block">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 sm:pl-4 sm:border-l border-gray-200">
                        <div
                            class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-sm flex-shrink-0">
                            A</div>
                        <span
                            class="text-sm font-medium text-gray-700 hidden sm:inline truncate max-w-[140px]">{{ auth()->user()->full_name ?? 'Admin' }}</span>
                    </div>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div
                        class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium animate-fade-in">
                        ✓ {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div
                        class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium animate-fade-in">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('sidebar-open');
            document.getElementById('sidebarOverlay').classList.add('overlay-visible');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('sidebar-open');
            document.getElementById('sidebarOverlay').classList.remove('overlay-visible');
            document.body.style.overflow = '';
        }

        // Reset drawer state when resizing up to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebarOverlay').classList.remove('overlay-visible');
                document.body.style.overflow = '';
            }
        });
    </script>

</body>

</html>
