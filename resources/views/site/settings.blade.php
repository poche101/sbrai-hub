<x-site-layout title="Settings — Sbrai Solutions" page="settings">

    <div id="settings-page" class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-1" data-i18n="settings">Settings</h1>
        <p class="text-sm text-gray-500 mb-6">Manage what you're notified about and what other users can see.</p>

        <p id="settings-error" class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl p-3 mb-4"></p>
        <p id="settings-success" class="hidden text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl p-3 mb-4"></p>

        <form id="settings-form" class="space-y-8">

            <section>
                <h2 class="font-semibold text-gray-900 mb-3">Notifications</h2>
                <div class="space-y-3">
                    <label class="flex items-center justify-between border border-gray-200 rounded-xl p-3 bg-white">
                        <span class="text-sm text-gray-700">New listings matching your interests</span>
                        <input type="checkbox" name="notifications.new_listings" class="h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </label>
                    <label class="flex items-center justify-between border border-gray-200 rounded-xl p-3 bg-white">
                        <span class="text-sm text-gray-700">Price drops on favourited listings</span>
                        <input type="checkbox" name="notifications.price_drops" class="h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </label>
                    <label class="flex items-center justify-between border border-gray-200 rounded-xl p-3 bg-white">
                        <span class="text-sm text-gray-700">New messages</span>
                        <input type="checkbox" name="notifications.messages" class="h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </label>
                    <label class="flex items-center justify-between border border-gray-200 rounded-xl p-3 bg-white">
                        <span class="text-sm text-gray-700">Promotions and news from Sbrai</span>
                        <input type="checkbox" name="notifications.promotions" class="h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </label>
                </div>
            </section>

            <section>
                <h2 class="font-semibold text-gray-900 mb-3">Privacy</h2>
                <div class="space-y-3">
                    <label class="flex items-center justify-between border border-gray-200 rounded-xl p-3 bg-white">
                        <span class="text-sm text-gray-700">Show my online status</span>
                        <input type="checkbox" name="privacy.show_online_status" class="h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </label>
                    <label class="flex items-center justify-between border border-gray-200 rounded-xl p-3 bg-white">
                        <span class="text-sm text-gray-700">Show my phone number to vendors/buyers I chat with</span>
                        <input type="checkbox" name="privacy.show_phone" class="h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </label>
                    <label class="flex items-center justify-between border border-gray-200 rounded-xl p-3 bg-white">
                        <span class="text-sm text-gray-700">Allow new people to message me</span>
                        <input type="checkbox" name="privacy.allow_messages" class="h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    </label>
                </div>
            </section>

            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm transition">
                Save settings
            </button>
        </form>
    </div>

</x-site-layout>
