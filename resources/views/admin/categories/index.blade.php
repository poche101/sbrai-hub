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

  <a href= "{{ route('admin.categories.edit', $cat) }}" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Edit</a>

  <form method="POST" action="{{ route('admin.categories.destroy', ['category' => $cat]) }}" onsubmit="return confirm('Delete this category?')">
    @csrf @method('DELETE')
    <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 transition">Delete</button>
  </form>

</div>
    </div>
    @empty
    <div class="px-6 py-8 text-center text-sm text-gray-400">No {{ strtolower($label) }} categories yet.</div>
    @endforelse
  </div>
</div>
@endforeach

</x-admin-layout>
