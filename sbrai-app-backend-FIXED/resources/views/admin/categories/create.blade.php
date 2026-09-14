<x-admin-layout title="New Category" subtitle="Add a category for vendors to list under">

<div class="max-w-xl">
  <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-400 hover:text-gray-600 flex items-center gap-1 mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Back to Categories
  </a>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category Name</label>
        <input type="text" name="name" required placeholder="e.g. Roofing Sheets"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Listing Type</label>
        <div class="grid grid-cols-3 gap-2">
          @foreach(['product' => 'Product', 'service' => 'Service', 'property' => 'Property'] as $val => $label)
          <label class="cursor-pointer">
            <input type="radio" name="listing_type" value="{{ $val }}" class="hidden peer" {{ $val === 'product' ? 'checked' : '' }}>
            <div class="peer-checked:bg-orange-50 peer-checked:border-orange-500 peer-checked:text-orange-600 border border-gray-200 rounded-xl py-2.5 text-center text-sm font-medium text-gray-500 transition">
              {{ $label }}
            </div>
          </label>
          @endforeach
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Icon (Emoji)</label>
        <input type="text" name="icon" placeholder="e.g. 🏠" maxlength="10"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
        <p class="text-xs text-gray-400 mt-1.5">Used as the category thumbnail in the app's category grid.</p>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Cover Image (Optional)</label>
        <input type="file" name="image" accept="image/*"
          class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-600 file:font-medium file:text-sm hover:file:bg-orange-100">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sort Order</label>
        <input type="number" name="sort_order" value="0" min="0"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>

      <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold text-sm py-3.5 rounded-xl shadow-md hover:opacity-90 transition">
        Create Category
      </button>
    </form>
  </div>
</div>

</x-admin-layout>
