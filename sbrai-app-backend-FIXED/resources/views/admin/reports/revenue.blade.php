<x-admin-layout title="Revenue Reports" subtitle="Track platform earnings, subscriptions, and multi-currency transactions">

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Completed NGN Revenue</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-2">₦{{ number_format($financials['total_ngn'], 2) }}</h3>
            <p class="text-xs text-orange-500 mt-1">Pending: ₦{{ number_format($financials['pending_ngn'], 2) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Completed ESPEES Revenue</p>
            <h3 class="text-2xl font-bold text-orange-600 mt-2">{{ number_format($financials['total_espees'], 2) }} ESP</h3>
            <p class="text-xs text-gray-400 mt-1">Pending: {{ number_format($financials['pending_espees'], 2) }} ESP</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Subscription Earnings</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-2">₦{{ number_format($financials['sub_earnings_ngn'], 2) }}</h3>
            <p class="text-xs text-green-500 mt-1">Active platform upgrades</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 text-base">Monthly Revenue Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3.5">Billing Month</th>
                        <th class="px-6 py-3.5">Transactions Count</th>
                        <th class="px-6 py-3.5">NGN Volume (₦)</th>
                        <th class="px-6 py-3.5">ESPEES Volume (ESP)</th>
                    </tr>
                </thead>
              <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($monthlyRevenue as $row)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($row->month . '-01')->format('F Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $row->total_transactions }} txs
                        </td>
                        <td class="px-6 py-4 text-gray-900 font-semibold">
                            ₦{{ number_format($row->total_ngn, 2) }}
                        </td>
                        <td class="px-6 py-4 text-orange-600 font-semibold">
                            {{ number_format($row->total_espees, 2) }} ESP
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                            No revenue history tracked in the database yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-admin-layout>
