<x-site-layout title="Sbrai Solutions — Verified Nigerian Marketplace" page="home">

    <section class="relative overflow-hidden pt-12 pb-32 bg-white">

        {{-- house photo — already soft-focus, so only a light blur is applied on top (avoids turning it to mush) --}}
        <div class="absolute -inset-4 bg-cover bg-center blur-sm scale-105 pointer-events-none" style="background-image: url('/images/blurr.jpg');"></div>

        {{-- warm wash over the photo — white gradient, uniform left-to-right so the photo shows evenly across the whole width, not just one corner --}}
        <div class="absolute inset-0 bg-gradient-to-b from-white/45 to-white/25 pointer-events-none"></div>

        {{-- extra scrim behind the text column only, so the headline stays readable without hiding the photo everywhere else --}}
        <div class="absolute inset-y-0 left-0 w-full lg:w-2/3 bg-gradient-to-r from-white/75 via-white/40 to-transparent pointer-events-none"></div>

        {{-- soft warm glow, positioned where the phone/podium sits in the reference shot --}}
        <div class="absolute top-16 right-0 lg:right-10 w-[420px] h-[420px] sm:w-[560px] sm:h-[560px] bg-orange-300/30 rounded-full blur-3xl pointer-events-none"></div>

        {{-- dot texture, faded toward the center so it reads like corner accents (as in the reference) rather than a flat uniform tile --}}
        <div class="absolute inset-0 bg-[radial-gradient(#ea580c_1.5px,transparent_1.5px)] [background-size:26px_26px] opacity-[0.14] [mask-image:radial-gradient(ellipse_75%_75%_at_50%_10%,black,transparent)] pointer-events-none"></div>

        {{-- floor gradient bleeding up from the bottom, matching the reference's warm orange floor tone under the wave --}}
        <div class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-orange-100/60 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Buy Smarter. <br>
                        <span class="text-orange-600">Sell Better.</span> <br>
                        Grow Together.
                    </h1>

                    <p class="text-gray-600 text-base sm:text-lg max-w-xl mx-auto lg:mx-0">
                        Sbrai Solutions connects buyers with trusted vendors. Discover quality products and services, compare options, and shop with confidence.
                    </p>

                    {{-- Search bar: stacks input above button on very small phones
                         (flex-col below sm) instead of squeezing both into one row,
                         which was tight on ~360px screens. Side-by-side from sm up. --}}
                    <form action="/browse" method="get" class="max-w-2xl mx-auto lg:mx-0 bg-white p-2 rounded-2xl shadow-lg border border-gray-100 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <div class="flex-1 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                type="text"
                                name="q"
                                placeholder="What are you looking for?"
                                class="w-full py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none bg-transparent"
                            >
                        </div>
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-6 py-3.5 rounded-xl transition flex items-center justify-center gap-2 shrink-0 shadow-sm">
                            <span>Search</span>
                        </button>
                    </form>

                    {{-- Trust features: centered under the centered heading on mobile,
                         reverts to left-aligned once the 3-col grid kicks in at sm. --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 max-w-2xl mx-auto lg:mx-0 text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Trusted Vendors</h4>
                                <p class="text-xs text-gray-500">Verified & Reliable</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-center sm:justify-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Great Prices</h4>
                                <p class="text-xs text-gray-500">Best Value Deals</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-center sm:justify-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Secure Payments</h4>
                                <p class="text-xs text-gray-500">Safe & Protected</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 relative flex justify-center items-center">
                    <img
                        src="/images/sbrai-hero.png"
                        alt="Sbrai Marketplace App and Shopping Bag"
                        class="w-full max-w-lg lg:max-w-none h-auto object-contain drop-shadow-2xl relative z-10"
                    >
                </div>

            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 overflow-hidden leading-none z-20">
            <svg class="relative block w-full h-12 sm:h-20 text-orange-600" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0 C150,90 350,-40 500,65 C650,170 900,10 1200,40 L1200,120 L0,120 Z" fill="currentColor"></path>
            </svg>
        </div>
    </section>

    <section class="bg-orange-600 text-white relative z-20 pb-12 pt-2 border-b border-orange-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center md:text-left">

                <div class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-3">
                    <div class="p-3 bg-white/10 rounded-2xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-orange-100 font-medium">Trusted by</p>
                        <p class="text-2xl font-black">10,000+</p>
                        <p class="text-xs text-orange-200">Happy Customers</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-3">
                    <div class="p-3 bg-white/10 rounded-2xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0V10m0 0V5m0 5h5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-orange-100 font-medium">Over</p>
                        <p class="text-2xl font-black">5,000+</p>
                        <p class="text-xs text-orange-200">Verified Vendors</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-3">
                    <div class="p-3 bg-white/10 rounded-2xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-orange-100 font-medium">More than</p>
                        <p class="text-2xl font-black">50,000+</p>
                        <p class="text-xs text-orange-200">Products & Services</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-3">
                    <div class="p-3 bg-white/10 rounded-2xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-orange-100 font-medium">Secure & Reliable</p>
                        <p class="text-2xl font-black">100%</p>
                        <p class="text-xs text-orange-200">Satisfaction</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 pb-16">
        <div class="flex flex-col sm:flex-row gap-6 items-start">
            <x-category-sidebar :categories="$categories" />

            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Trending listings</h2>
                    <a href="/browse" class="text-sm text-orange-600 font-medium hover:underline">View all</a>
                </div>

                @if ($trending->isEmpty())
                    <p class="text-sm text-gray-400 py-10 text-center border border-dashed border-gray-200 rounded-lg">
                        No listings yet. Be the first vendor to <a href="/post-ad" class="text-orange-600 underline">post one</a>.
                    </p>
                @else
                    {{-- 3-col grid now waits until md instead of sm — at the sm
                         breakpoint the sidebar has just appeared alongside it, so
                         staying at 2 columns there avoids overly cramped cards. --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($trending as $listing)
                            <a href="/listing/{{ $listing->id }}" class="block rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition bg-white">
                                <div class="aspect-[4/3] bg-gray-100">
                                    @if (!empty($listing->image_urls[0]))
                                        <img src="{{ $listing->image_urls[0] }}" alt="{{ $listing->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-sm">No photo</div>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <p class="font-semibold text-gray-900">₦{{ number_format($listing->price) }} <span class="font-normal text-gray-500 text-sm">/ {{ $listing->price_unit }}</span></p>
                                    <p class="text-sm text-gray-800 truncate mt-1">{{ $listing->title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $listing->location }}, {{ $listing->state }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

</x-site-layout>
