<x-site-layout title="Post an Ad — Sbrai Solutions" page="post-ad">

    <div id="post-ad-page" class="max-w-2xl mx-auto px-4 sm:px-6 py-10">

        <div id="post-ad-gate" class="hidden border border-amber-200 bg-amber-50 rounded-lg p-6 text-center">
            <p id="post-ad-gate-message" class="text-amber-800"></p>
            <div class="mt-4 flex gap-4 justify-center">
                <a id="post-ad-gate-cta" href="#" class="hidden text-sm font-semibold bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700"></a>
                <a href="/browse" class="text-sm font-medium text-emerald-700 hover:underline self-center">Back to browsing</a>
            </div>
        </div>

        <div id="post-ad-wizard" class="hidden">

            <div class="flex items-center gap-2 mb-8">
                @foreach ([1 => 'Type & category', 2 => 'Photos', 3 => 'Details'] as $n => $label)
                    <div class="flex items-center gap-2 {{ $n < 3 ? 'flex-1' : '' }}">
                        <span data-step-dot="{{ $n }}" class="sbrai-step-dot w-7 h-7 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold flex items-center justify-center shrink-0">{{ $n }}</span>
                        <span class="text-xs text-gray-500 hidden sm:inline">{{ $label }}</span>
                        @if ($n < 3)
                            <div class="flex-1 h-px bg-gray-200"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- ── Step 1 ─────────────────────────────────────────── --}}
            <div data-step="1">
                <h2 class="font-semibold text-gray-900 mb-4">What are you listing?</h2>
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <button type="button" data-type-option="product" class="sbrai-option-btn sbrai-option-active border border-gray-200 rounded-lg p-4 text-center">
                        <span class="block text-2xl mb-1">🧱</span>
                        <span class="text-sm font-medium">Product</span>
                    </button>
                    <button type="button" data-type-option="service" class="sbrai-option-btn border border-gray-200 rounded-lg p-4 text-center">
                        <span class="block text-2xl mb-1">🛠️</span>
                        <span class="text-sm font-medium">Service</span>
                    </button>
                    <button type="button" data-type-option="property" class="sbrai-option-btn border border-gray-200 rounded-lg p-4 text-center">
                        <span class="block text-2xl mb-1">🏠</span>
                        <span class="text-sm font-medium">Property</span>
                    </button>
                </div>

                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select id="field-category" class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 mb-6">
                    <option value="">Choose a category…</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <button id="step1-next" type="button" class="bg-emerald-600 text-white font-medium px-6 py-2.5 rounded-md hover:bg-emerald-700">
                    Continue
                </button>
            </div>

            {{-- ── Step 2 ─────────────────────────────────────────── --}}
            <div data-step="2" class="hidden">
                <h2 class="font-semibold text-gray-900 mb-2">Add photos</h2>
                <p class="text-sm text-gray-500 mb-4">Up to 5 photos. Listings with photos get far more views.</p>

                <label class="block border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-emerald-400 mb-4">
                    <input id="field-photos" type="file" accept="image/*" multiple class="hidden">
                    <span class="text-sm text-gray-500">Click to choose photos</span>
                </label>

                <div id="photo-preview" class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-6"></div>

                <div class="flex gap-3">
                    <button id="step2-back" type="button" class="border border-gray-300 px-6 py-2.5 rounded-md text-sm font-medium hover:bg-gray-50">Back</button>
                    <button id="step2-next" type="button" class="bg-emerald-600 text-white font-medium px-6 py-2.5 rounded-md hover:bg-emerald-700">Continue</button>
                </div>
            </div>

            {{-- ── Step 3 ─────────────────────────────────────────── --}}
            <div data-step="3" class="hidden">
                <h2 class="font-semibold text-gray-900 mb-4">Listing details</h2>

                <form id="post-ad-form" class="space-y-4">
                    <input type="hidden" id="field-type" name="type" value="product">
                    <p id="post-ad-error" class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-md p-2"></p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" required maxlength="255" class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" required maxlength="2000" rows="4" class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price (₦)</label>
                            <input type="number" name="price" min="0" step="0.01" required class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <input type="text" name="price_unit" required placeholder="bag, sq.m, job, night…" class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" name="location" required placeholder="e.g. Lekki Phase 1" class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                            <select name="state" required class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Choose…</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="property-attributes" class="hidden grid grid-cols-3 gap-4 border-t border-gray-100 pt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bedrooms</label>
                            <input type="number" name="bedrooms" min="0" class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bathrooms</label>
                            <input type="number" name="bathrooms" min="0" class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Furnishing</label>
                            <select name="furnishing" class="w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">—</option>
                                <option value="furnished">Furnished</option>
                                <option value="semi-furnished">Semi-furnished</option>
                                <option value="unfurnished">Unfurnished</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button id="step3-back" type="button" class="border border-gray-300 px-6 py-2.5 rounded-md text-sm font-medium hover:bg-gray-50">Back</button>
                        <button type="submit" class="flex-1 bg-emerald-600 text-white font-medium py-2.5 rounded-md hover:bg-emerald-700">Publish listing</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</x-site-layout>
