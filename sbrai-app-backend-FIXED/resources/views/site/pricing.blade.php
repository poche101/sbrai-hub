<x-site-layout title="Pricing — Sbrai Solutions" page="pricing">

    <div id="pricing-page" class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-xl font-semibold text-gray-900 mb-1">Subscription</h1>
        <p class="text-sm text-gray-500 mb-6">
            An active annual subscription is required to post listings as a vendor.
        </p>

        <div id="sub-status-active" class="hidden mb-6 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">
            ✓ Active until <span id="sub-status-end-date"></span>
        </div>
        <div id="sub-status-inactive" class="hidden mb-6 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-md p-3">
            No active subscription yet.
        </div>
        <p class="text-xs text-gray-400 mb-6">
            Voucher balance: <span id="voucher-balance">—</span>
        </p>

        <div class="flex border-b border-gray-200 mb-6">
            <button data-pay-tab="paystack" class="flex-1 py-3 text-sm font-semibold border-b-2 border-emerald-600 text-emerald-700">
                Pay with Paystack — ₦20,000/yr
            </button>
            <button data-pay-tab="espees" class="flex-1 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500">
                Pay with Espees — 10/yr
            </button>
        </div>

        {{-- ── Paystack ─────────────────────────────────────────────── --}}
        <div data-pay-panel="paystack">
            <ol class="text-sm text-gray-600 space-y-2 mb-4 list-decimal list-inside">
                <li>Click "Open Paystack checkout" — it opens in a new tab.</li>
                <li>Complete the ₦20,000 payment there.</li>
                <li>Paste the payment reference from your receipt/email below and verify.</li>
            </ol>

            <a id="paystack-checkout-link" href="#" target="_blank" rel="noopener"
               class="inline-block bg-emerald-600 text-white font-medium px-6 py-2.5 rounded-md hover:bg-emerald-700 mb-4">
                Open Paystack checkout
            </a>

            <form id="paystack-verify-form" class="flex gap-2">
                <input id="paystack-reference" type="text" required placeholder="Payment reference"
                       class="flex-1 rounded-md border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                <button type="submit" class="bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-black">
                    Verify payment
                </button>
            </form>
            <p id="paystack-error" class="hidden text-xs text-red-600 mt-2"></p>
            <p id="paystack-success" class="hidden text-xs text-green-600 mt-2"></p>
        </div>

        {{-- ── Espees ───────────────────────────────────────────────── --}}
        <div data-pay-panel="espees" class="hidden">
            <form id="espees-form" class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Espees wallet ID</label>
                    <input name="wallet_id" type="text" required class="w-full rounded-md border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wallet PIN</label>
                    <input name="pin" type="password" required minlength="4" maxlength="6" class="w-full rounded-md border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <input type="hidden" name="amount" value="10">
                <input type="hidden" name="description" value="Annual Subscription - Espees">
                <button type="submit" class="bg-emerald-600 text-white font-medium px-6 py-2.5 rounded-md hover:bg-emerald-700">
                    Pay 10 Espees
                </button>
            </form>
            <p id="espees-error" class="hidden text-xs text-red-600 mt-2"></p>
            <p id="espees-success" class="hidden text-xs text-green-600 mt-2"></p>
        </div>

        <h2 class="font-medium text-gray-900 mt-10 mb-3">Transaction history</h2>
        <div id="transactions-list" class="text-sm text-gray-600 space-y-2"></div>
        <p id="transactions-empty" class="hidden text-sm text-gray-400">No transactions yet.</p>

        <div class="flex gap-3 mt-8">
            <a href="/post-ad" class="text-sm font-medium text-emerald-700 hover:underline">← Back to Post an Ad</a>
        </div>
    </div>

</x-site-layout>
