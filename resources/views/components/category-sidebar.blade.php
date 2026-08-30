{{--
    resources/views/components/category-sidebar.blade.php

    Vertical "All categories" aside — pulls live from the Category model
    (the same data the admin manages via New/Edit Category), so any page
    can drop this in without wiring up the data itself. Pass :categories
    explicitly if you ever need to override what it shows (e.g. a
    pre-filtered or cached collection); otherwise it queries the admin's
    categories directly.

    Shows the uploaded cover image as a small thumbnail when a category
    has one, falling back to the emoji icon when it doesn't.

    Usage:
        <x-category-sidebar />
        <x-category-sidebar :active="$activeCategoryName" />
        <x-category-sidebar :listing-type="'product'" />
        <x-category-sidebar :categories="$categories" :active="$activeCategoryName" />
--}}
@php
    $active = $active ?? null;

    $categories = $categories ?? \App\Models\Category::query()
        ->when($listingType ?? null, fn ($query, $type) => $query->where('listing_type', $type))
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();
@endphp

<aside class="w-full sm:w-56 shrink-0 bg-white border border-gray-200 rounded-lg overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-900">All categories</h2>
    </div>

    <nav class="py-1 max-h-[520px] overflow-y-auto">
        @forelse ($categories as $category)
            @php $isActive = $active === $category->name; @endphp
            <a
                href="/browse?category={{ urlencode($category->name) }}"
                class="group flex items-center justify-between gap-3 px-4 py-2.5 text-sm transition-colors
                       {{ $isActive ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-orange-600' }}"
            >
                <span class="flex items-center gap-3 min-w-0">
                    <span class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-base shrink-0 overflow-hidden">
                        @if($category->image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            {{ $category->icon ?? '🏷️' }}
                        @endif
                    </span>
                    <span class="truncate">{{ $category->name }}</span>
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-gray-300 group-hover:text-orange-500 {{ $isActive ? 'text-orange-500' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>
        @empty
            <p class="px-4 py-3 text-sm text-gray-400">
                No categories yet.
            </p>
        @endforelse
    </nav>
</aside>
