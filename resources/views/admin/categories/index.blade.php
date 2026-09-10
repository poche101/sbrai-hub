<x-admin-layout title="Categories" subtitle="Manage listing categories shown to vendors and buyers">

  @if(session('success'))
  <div class="mb-4 p-4 text-sm text-green-700 bg-green-50 rounded-xl border border-green-100">
      {{ session('success') }}
  </div>
  @endif

  @if(session('error') || $errors->any())
  <div class="mb-4 p-4 text-sm text-red-700 bg-red-50 rounded-xl border border-red-100">
      {{ session('error') ?? $errors->first() }}
  </div>
  @endif

  <div x-data="{ deleteModalOpen: false, deleteUrl: '', categoryName: '' }">
    <div class="flex items-center justify-between mb-6">
      <p class="text-sm text-gray-500">Categories appear in the app's home screen and "Post Ad" flow. Only admins can create or edit them.</p>
      <a href="{{ route('admin.categories.create') }}" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm hover:opacity-90 transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Category
      </a>
    </div>

    @foreach(['product' => 'Products', 'service' => 'Services', 'property' => 'Properties'] as $type => $label)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-900">{{ $label }}</h3>
        <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">
          {{ $categories->get($type, collect())->count() }} categories
        </span>
      </div>
      <div class="divide-y divide-gray-50">
        @forelse($categories->get($type, collect()) as $cat)
        <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition">
          <div class="w-11 h-11 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-xl flex-shrink-0 overflow-hidden">
            @if($cat->image_url)
              <img src="{{ asset($cat->image_url) }}" alt="{{ $cat->name }}" class="w-full h-full object-cover">
            @else
              {{ $cat->icon ?? '📦' }}
            @endif
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2">
              <span class="font-semibold text-gray-900 text-sm">{{ $cat->name }}</span>
              @if(!$cat->is_active)
                <span class="text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full font-medium">Inactive</span>
              @endif
            </div>
            <div class="text-xs text-gray-400 mt-0.5">{{ $cat->listings_count }} listing(s) · slug: {{ $cat->slug }}</div>
          </div>
          <div class="flex items-center gap-2">

            <form method="POST" action="{{ route('admin.categories.toggle', ['category' => $cat]) }}">
              @csrf
              <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                {{ $cat->is_active ? 'Deactivate' : 'Activate' }}
              </button>
            </form>

            <a href="{{ route('admin.categories.edit', $cat) }}" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Edit</a>

            <button type="button"
                    @click="deleteUrl = '{{ route('admin.categories.destroy', ['category' => $cat]) }}'; categoryName = '{{ addslashes($cat->name) }}'; deleteModalOpen = true"
                    class="text-xs font-medium px-3 py-1.5 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 transition">
              Delete
            </button>

          </div>
        </div>
        @empty
        <div class="px-6 py-8 text-center text-sm text-gray-400">No {{ strtolower($label) }} categories yet.</div>
        @endforelse
      </div>
    </div>
    @endforeach

    <!-- Delete Confirmation Modal -->
<div x-show="deleteModalOpen"
     x-cloak
     @keydown.escape.window="deleteModalOpen = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

  <div @click.outside="deleteModalOpen = false"
       class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 transform transition-all"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="scale-95 opacity-0"
       x-transition:enter-end="scale-100 opacity-100">

    <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center mb-4 mx-auto">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
      </svg>
    </div>

    <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Delete Category</h3>
    <p class="text-sm text-gray-500 text-center mb-6">
      Are you sure you want to delete <span class="font-semibold text-gray-800" x-text="categoryName"></span>? This action cannot be undone.
    </p>

    <div class="flex items-center gap-3">
      <!-- Cancel Button -->
      <button type="button"
              @click.prevent="deleteModalOpen = false"
              class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
        Cancel
      </button>

      <!-- Submit Form -->
      <form :action="deleteUrl" method="POST" class="flex-1">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-sm font-semibold text-white hover:bg-red-700 transition shadow-sm">
          Confirm Delete
        </button>
      </form>
    </div>
  </div>
</div>
  </div>

</x-admin-layout>
