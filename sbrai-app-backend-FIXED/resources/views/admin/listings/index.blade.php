<x-admin-layout title="Platform Listings" subtitle="Monitor and manage products, services, and properties uploaded by vendors">

<div class="space-y-6">
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.listings.index') }}" class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div class="relative w-full max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search listings by title..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 bg-gray-50/50">
                <div class="absolute left-3.5 top-3.5 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="status" onchange="this.form.submit()" class="w-full sm:w-48 px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 bg-gray-50/50 text-gray-600">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.listings.index') }}" class="text-xs font-semibold text-gray-400 hover:text-gray-600 underline">Clear Filters</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Listing Details</th>
                        <th class="px-6 py-4">Vendor Partner</th>
                        <th class="px-6 py-4">Price Assessment</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($listings as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $item->title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">Created on {{ $item->created_at->format('M d, Y') }} · Category: {{ ucfirst($item->category ?? 'General') }}</div>
                        </td>

                        <td class="px-6 py-4">
                            @if($item->vendor)
                                <div class="font-medium text-gray-800">{{ $item->vendor->business_name ?? $item->vendor->full_name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">ID: {{ substr($item->vendor->id, 0, 8) }}...</div>
                            @else
                                <span class="text-xs text-gray-400 italic">Unknown Vendor</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-900">
                            ₦{{ number_format($item->price, 2) }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $item->status === 'active' ? 'bg-green-50 text-green-700 border border-green-100' : '' }}
                                {{ $item->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                {{ $item->status === 'suspended' ? 'bg-red-50 text-red-700 border border-red-100' : '' }}
                            ">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <span class="text-xs text-gray-400 italic">Read-Only View</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            No listings match the database query filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($listings->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                {{ $listings->links() }}
            </div>
        @endif
    </div>
</div>

</x-admin-layout>
