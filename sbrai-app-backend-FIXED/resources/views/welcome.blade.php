<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sbrai Solutions — Build with people you can actually trust</title>
    <meta name="description" content="Sbrai Solutions connects buyers with verified vendors of building materials, artisan services and property — in every state in Nigeria.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#FBF6F0] text-gray-900">

    @php
        // Fallback data so this page renders correctly before a controller
        // wires up real data. Pass $categories / $listings from your
        // HomeController and these are ignored automatically.
        $categories = $categories ?? [
            ['label' => 'Cement', 'slug' => 'cement', 'count' => 1, 'color' => 'from-orange-400 to-red-500'],
            ['label' => 'Roofing Sheets', 'slug' => 'roofing-sheets', 'count' => 1, 'color' => 'from-slate-400 to-slate-600'],
            ['label' => 'Tiles & Marble', 'slug' => 'tiles-marble', 'count' => 1, 'color' => 'from-stone-300 to-stone-500'],
            ['label' => 'Timber & Wood', 'slug' => 'timber-wood', 'count' => 1, 'color' => 'from-amber-500 to-amber-700'],
            ['label' => 'Iron Rods', 'slug' => 'iron-rods', 'count' => 1, 'color' => 'from-zinc-400 to-zinc-600'],
            ['label' => 'Plumbing', 'slug' => 'plumbing', 'count' => 1, 'color' => 'from-sky-400 to-sky-600'],
            ['label' => 'Electrical', 'slug' => 'electrical', 'count' => 0, 'color' => 'from-yellow-400 to-yellow-600'],
            ['label' => 'Masons', 'slug' => 'masons', 'count' => 1, 'color' => 'from-orange-300 to-orange-500'],
            ['label' => 'Carpenters', 'slug' => 'carpenters', 'count' => 0, 'color' => 'from-amber-600 to-amber-800'],
            ['label' => 'Painters', 'slug' => 'painters', 'count' => 0, 'color' => 'from-indigo-400 to-indigo-600'],
        ];

        $listings = $listings ?? [
            [
                'title' => 'Dangote Cement 3X — 50kg Bag (Wholesale)',
                'category' => 'Cement', 'badge' => 'Product', 'color' => 'from-orange-400 to-red-500',
                'price' => '₦9,800', 'unit' => 'per bag', 'location' => 'Ikeja, Lagos',
                'vendor' => 'Adeyemi Building Depot', 'verified' => true, 'rating' => '4.8',
                'url' => '/listing/dangote-cement-3x',
            ],
            [
                'title' => 'Stepped Aluminium Roofing Sheet — 0.55mm',
                'category' => 'Roofing Sheets', 'badge' => 'Product', 'color' => 'from-teal-400 to-cyan-600',
                'price' => '₦7,200', 'unit' => 'per metre', 'location' => 'Port Harcourt, Rivers',
                'vendor' => 'Ejike Roof Systems', 'verified' => true, 'rating' => '4.6',
                'url' => '/listing/aluminium-roofing-stepped',
            ],
            [
                'title' => 'Block Laying & Plastering Crew (6-man)',
                'category' => 'Masons', 'badge' => 'Service', 'color' => 'from-orange-300 to-orange-600',
                'price' => '₦45,000', 'unit' => 'per day', 'location' => 'Ikorodu, Lagos',
                'vendor' => 'Bola & Sons Masonry', 'verified' => true, 'rating' => '4.7',
                'url' => '/listing/block-laying-plastering-crew',
            ],
        ];
    @endphp

    {{-- ── Top header: logo, search, sign in ─────────────────────── --}}
    <header class="bg-[#FBF6F0]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center gap-6">
            <a href="/" class="flex items-center gap-2 shrink-0">
                <span class="w-9 h-9 rounded-md bg-orange-500 flex items-center justify-center text-white font-bold text-sm">S</span>
                <span class="font-bold text-lg text-gray-900">Sbrai</span>
            </a>

            <form action="/browse" method="GET" class="flex-1 flex items-center bg-white border border-gray-200 rounded-lg overflow-hidden">
                <span class="pl-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                </span>
                <input type="text" name="q" placeholder="What are you looking for?" class="flex-1 border-0 focus:ring-0 text-sm px-3 py-2.5 placeholder-gray-400">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 py-2.5 m-1 rounded-md">
                    Search
                </button>
            </form>

            <div class="flex items-center gap-5 shrink-0 text-sm">
                <button type="button" class="flex items-center gap-1.5 text-gray-700 border border-gray-200 rounded-md px-3 py-2 hover:bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/>
                    </svg>
                    English
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <a href="/auth" class="font-medium text-gray-700 hover:text-orange-600">Sign in</a>
                <a href="/auth?mode=register&role=vendor" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-4 py-2 rounded-md">Sell on Sbrai</a>
            </div>
        </div>

        {{-- ── Sub-nav: primary links + category strip ─────────────── --}}
        <div class="border-t border-b border-gray-200/70">
            <div class="max-w-7xl mx-auto px-6 flex items-center gap-6 py-3 text-sm font-medium text-gray-600 overflow-x-auto no-scrollbar whitespace-nowrap">
                <a href="/browse" class="hover:text-orange-600">Browse</a>
                <a href="/for-vendors" class="hover:text-orange-600">For Vendors</a>
                <a href="/how-it-works" class="hover:text-orange-600">How it works</a>
                <a href="/pricing" class="hover:text-orange-600">Pricing</a>
                <span class="h-4 w-px bg-gray-300 mx-1"></span>
                @foreach (($categories ?? []) as $category)
                    <a href="/browse?category={{ $category['slug'] }}" class="hover:text-orange-600">{{ $category['label'] }}</a>
                @endforeach
            </div>
        </div>
    </header>

    {{-- ── Hero ─────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ $heroImage ?? '/images/hero-materials.jpg' }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-[#241108]/90 via-[#241108]/70 to-[#241108]/20"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 py-20">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-orange-300 text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                </svg>
                KYC-verified vendors only
            </span>

            <h1 class="text-white font-extrabold text-4xl sm:text-5xl lg:text-6xl leading-tight max-w-2xl">
                Build with people you can actually trust.
            </h1>

            <p class="text-gray-200 text-lg mt-5 max-w-xl">
                Sbrai Solutions connects buyers with verified vendors of building materials, artisan services and property — in every state in Nigeria.
            </p>

            <form action="/browse" method="GET" class="mt-8 bg-white rounded-xl p-2 flex flex-col sm:flex-row items-stretch gap-2 max-w-3xl shadow-lg">
                <div class="flex-1 flex items-center px-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input type="text" name="q" placeholder="Cement, tiles, mason, 3 bedroom flat…" class="w-full border-0 focus:ring-0 text-sm px-3 py-2.5 placeholder-gray-400">
                </div>
                <select name="state" class="border border-gray-200 rounded-lg text-sm px-3 py-2.5 text-gray-700">
                    <option>All states</option>
                    @foreach (($states ?? ['Lagos', 'Rivers', 'FCT']) as $state)
                        <option>{{ $state }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-2.5 rounded-lg">
                    Search
                </button>
            </form>

            <div class="flex items-center gap-10 mt-10">
                @foreach ([
                    ['value' => $statesCovered ?? '37', 'label' => 'States covered'],
                    ['value' => ($vendorsVerifiedPct ?? '100') . '%', 'label' => 'Vendors KYC-verified'],
                    ['value' => '24/7', 'label' => 'In-app chat'],
                ] as $stat)
                    <div>
                        <div class="text-white text-2xl font-bold">{{ $stat['value'] }}</div>
                        <div class="text-gray-300 text-sm">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Categories + trending listings ─────────────────────────── --}}
    <section class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex flex-col sm:flex-row gap-6 items-start">
            <x-category-sidebar :categories="$categories ?? null" :active="$activeCategory ?? null" />

            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">Trending listings</h2>
                    <a href="/browse" class="text-orange-600 font-semibold text-sm flex items-center gap-1 hover:underline">
                        See all
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach (($listings ?? []) as $listing)
                        <a href="{{ $listing['url'] ?? '#' }}" class="block bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="relative h-40 bg-gradient-to-br {{ $listing['color'] ?? 'from-gray-300 to-gray-500' }} flex items-end p-3">
                                <span class="absolute top-3 left-3 bg-white/90 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    {{ $listing['badge'] ?? 'Product' }}
                                </span>
                                <span class="text-white font-bold text-lg drop-shadow">{{ $listing['category'] ?? '' }}</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 text-sm leading-snug">{{ $listing['title'] }}</h3>
                                <p class="mt-2 text-sm">
                                    <span class="text-orange-600 font-bold">{{ $listing['price'] }}</span>
                                    <span class="text-gray-400">{{ $listing['unit'] ?? '' }}</span>
                                </p>
                                <p class="flex items-center gap-1 text-xs text-gray-400 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ $listing['location'] }}
                                </p>
                                <div class="flex items-center justify-between mt-3 text-xs">
                                    <span class="flex items-center gap-1 text-gray-600 font-medium">
                                        {{ $listing['vendor'] }}
                                        @if ($listing['verified'] ?? false)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="currentColor"><path d="m9 12 2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        @endif
                                    </span>
                                    <span class="flex items-center gap-1 text-orange-500 font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z"/></svg>
                                        {{ $listing['rating'] }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-gray-200 mt-10">
        <div class="max-w-7xl mx-auto px-6 py-10 text-sm text-gray-500 flex flex-col sm:flex-row justify-between gap-4">
            <p>&copy; {{ date('Y') }} Sbrai Solutions Limited · sbraisolutions.com</p>
            <div class="flex gap-4">
                <a href="/browse" class="hover:text-orange-600">Browse</a>
                <a href="/post-ad" class="hover:text-orange-600">Sell on Sbrai</a>
            </div>
        </div>
    </footer>

</body>
</html>ss