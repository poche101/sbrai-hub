<x-site-layout :title="$listing->title . ' — Sbrai Solutions'" page="listing">

    <div id="listing-page" data-listing-id="{{ $listing->id }}" data-vendor-id="{{ $listing->vendor_id }}" class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2">
                <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden mb-3">
                    @if (!empty($listing->image_urls[0]))
                        <img id="main-photo" src="{{ $listing->image_urls[0] }}" alt="{{ $listing->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">No photos yet</div>
                    @endif
                </div>

                @if (count($listing->image_urls ?? []) > 1)
                    <div class="flex gap-2 mb-6">
                        @foreach ($listing->image_urls as $url)
                            <button
                                type="button"
                                onclick="document.getElementById('main-photo').src = '{{ $url }}'"
                                class="w-16 h-16 rounded-md overflow-hidden border border-gray-200"
                            >
                                <img src="{{ $url }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                <h1 class="text-2xl font-bold text-gray-900">{{ $listing->title }}</h1>
                <p class="text-gray-500 mt-1">{{ $listing->location }}, {{ $listing->state }}</p>
                <p class="text-2xl font-bold text-emerald-700 mt-4">
                    ₦{{ number_format($listing->price) }}
                    <span class="text-sm font-normal text-gray-500">/ {{ $listing->price_unit }}</span>
                </p>

                @if ($listing->type === 'property' && !empty($listing->attributes))
                    <div class="flex gap-6 mt-4 text-sm text-gray-700">
                        @if (!empty($listing->attributes['bedrooms']))
                            <span>🛏️ {{ $listing->attributes['bedrooms'] }} bed</span>
                        @endif
                        @if (!empty($listing->attributes['bathrooms']))
                            <span>🛁 {{ $listing->attributes['bathrooms'] }} bath</span>
                        @endif
                        @if (!empty($listing->attributes['furnishing']))
                            <span>🛋️ {{ ucfirst($listing->attributes['furnishing']) }}</span>
                        @endif
                    </div>
                @endif

                <h2 class="font-semibold text-gray-900 mt-6 mb-2">Description</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $listing->description }}</p>
            </div>

            <div>
                <div class="border border-gray-200 rounded-lg p-4 bg-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">
                                {{ $listing->vendor->business_name ?? $listing->vendor->full_name }}
                            </p>
                            @if ($listing->vendor->is_verified)
                                <p class="text-xs text-green-600 mt-0.5">✓ Verified vendor</p>
                            @else
                                <p class="text-xs text-gray-400 mt-0.5">Not yet verified</p>
                            @endif
                        </div>
                        <button id="fav-btn" type="button" class="sbrai-fav-btn w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center" aria-label="Save listing">♥</button>
                    </div>

                    <div class="mt-4 space-y-2">
                        <button id="chat-btn" type="button" class="w-full bg-emerald-600 text-white font-medium py-2.5 rounded-md hover:bg-emerald-700">
                            Chat with vendor
                        </button>
                        <button id="call-btn" type="button" class="w-full border border-emerald-600 text-emerald-700 font-medium py-2.5 rounded-md hover:bg-emerald-50">
                            Voice / video call
                        </button>
                    </div>

                    <p id="contact-gate-note" class="hidden mt-3 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-md p-2"></p>

                    <div id="chat-compose" class="hidden mt-3">
                        <form id="chat-compose-form">
                            <p id="chat-compose-error" class="hidden text-xs text-red-600 bg-red-50 border border-red-200 rounded-md p-2 mb-2"></p>
                            <textarea
                                id="chat-compose-input" rows="2" required maxlength="1000"
                                placeholder="Say hello and ask your question…"
                                class="w-full text-sm rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                            ></textarea>
                            <button type="submit" class="mt-2 w-full bg-emerald-600 text-white text-sm font-medium py-2 rounded-md hover:bg-emerald-700">
                                Send message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-site-layout>
