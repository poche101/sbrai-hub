<x-admin-layout title="Edit Category" subtitle="Update category details">

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

<div class="max-w-xl">
  <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-400 hover:text-gray-600 flex items-center gap-1 mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Back to Categories
  </a>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-5">
      @csrf @method('PUT')

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category Name</label>
        <input type="text" name="name" required value="{{ $category->name }}"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Listing Type</label>
        <div class="grid grid-cols-3 gap-2">
          @foreach(['product' => 'Product', 'service' => 'Service', 'property' => 'Property'] as $val => $label)
          <label class="cursor-pointer">
            <input type="radio" name="listing_type" value="{{ $val }}" class="hidden peer" {{ $category->listing_type === $val ? 'checked' : '' }}>
            <div class="peer-checked:bg-orange-50 peer-checked:border-orange-500 peer-checked:text-orange-600 border border-gray-200 rounded-xl py-2.5 text-center text-sm font-medium text-gray-500 transition">
              {{ $label }}
            </div>
          </label>
          @endforeach
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Icon (Emoji)</label>
        <input type="text" name="icon" maxlength="10" value="{{ $category->icon }}"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>

      @if($category->image_url)
        <img src="{{ $category->image_url }}" class="w-20 h-20 rounded-xl object-cover border border-gray-200">
      @endif

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Replace Cover Image</label>
        <input type="file" name="image" accept="image/*"
          class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-600 file:font-medium file:text-sm hover:file:bg-orange-100">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sort Order</label>
        <input type="number" name="sort_order" value="{{ $category->sort_order }}" min="0"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>

      <label class="flex items-center gap-2 text-sm text-gray-600">
        <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-orange-500 focus:ring-orange-500">
        Active (visible in app)
      </label>

      <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold text-sm py-3.5 rounded-xl shadow-md hover:opacity-90 transition">
        Save Changes
      </button>
    </form>
  </div>
</div>

</x-admin-layout>
