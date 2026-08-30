<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => 'Pricing — Sbrai Solutions','page' => 'pricing']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pricing — Sbrai Solutions','page' => 'pricing']); ?>

    <div id="pricing-page" class="max-w-4xl mx-auto px-4 sm:px-6 py-12 sm:py-16">

        <!-- Header Section -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="inline-block text-xs font-bold uppercase tracking-wider text-orange-600 bg-orange-50 border border-orange-200/60 px-3 py-1 rounded-full mb-3">Vendor Membership</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-3">Simple, transparent pricing</h1>
            <p class="text-base text-gray-600">
                An active annual subscription is required to post listings as a vendor on Sbrai Solutions.
            </p>
        </div>

        <!-- Status & Voucher Banners -->
        <div class="max-w-xl mx-auto mb-8 space-y-3">
            <div id="sub-status-active" class="hidden text-sm font-medium text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-2 shadow-sm">
                <i class="ti ti-circle-check text-lg text-emerald-600"></i>
                <span>Active subscription until <strong id="sub-status-end-date" class="font-semibold"></strong></span>
            </div>

            <div id="sub-status-inactive" class="hidden text-sm font-medium text-amber-800 bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-2 shadow-sm">
                <i class="ti ti-alert-circle text-lg text-amber-600"></i>
                <span>No active subscription yet. Choose a plan below to get started.</span>
            </div>

            <div class="flex items-center justify-between bg-white border-2 border-gray-200 rounded-2xl px-5 py-3.5 shadow-sm text-sm text-gray-700">
                <span class="font-medium text-gray-500">Voucher balance</span>
                <span id="voucher-balance" class="font-bold text-gray-900">—</span>
            </div>
        </div>

        <!-- Main Pricing Card Container -->
        <div class="max-w-xl mx-auto bg-white rounded-3xl border-2 border-gray-200 shadow-xl shadow-gray-200/50 p-6 sm:p-8 mb-12">

            <!-- Payment Gateway Tabs -->
            <div class="grid grid-cols-2 gap-2 p-1.5 bg-gray-100 rounded-2xl mb-8">
                <button data-pay-tab="paystack" class="py-2.5 text-sm font-semibold rounded-xl transition-all bg-white text-orange-600 shadow-sm">
                    Pay with Paystack
                </button>
                <button data-pay-tab="espees" class="py-2.5 text-sm font-semibold rounded-xl transition-all text-gray-500 hover:text-gray-900">
                    Pay with Espees
                </button>
            </div>

            <!-- ── Paystack Panel ─────────────────────────────────────────────── -->
            <div data-pay-panel="paystack" class="space-y-6">
                <!-- Pricing Callout Box -->
                <div class="bg-gradient-to-br from-orange-50/60 to-orange-100/30 border border-orange-200/80 rounded-2xl p-5 text-center">
                    <span class="text-xs font-bold uppercase tracking-wider text-orange-600 bg-white px-2.5 py-1 rounded-full shadow-xs">Annual Access</span>
                    <div class="mt-3 flex items-baseline justify-center gap-1">
                        <span class="text-4xl font-extrabold text-gray-900">₦20,000</span>
                        <span class="text-sm font-medium text-gray-500">/ 1 year plan</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Full access to vendor listings, chats, and calls.</p>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Payment steps</h3>
                    <ol class="text-sm text-gray-600 space-y-2.5 list-disc list-inside">
                        <li>Click <strong class="text-gray-800">"Open Paystack checkout"</strong> below (opens in a new tab).</li>
                        <li>Complete the secure ₦20,000 payment on Paystack.</li>
                        <li>Copy the payment reference from your receipt/email, paste it below, and click verify.</li>
                    </ol>
                </div>

                <a id="paystack-checkout-link" href="#" target="_blank" rel="noopener"
                   class="w-full inline-flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3.5 rounded-xl shadow-md hover:shadow-lg transition-all">
                    <i class="ti ti-external-link text-lg"></i>
                    Open Paystack checkout
                </a>

                <form id="paystack-verify-form" class="flex flex-col sm:flex-row gap-2.5 pt-2 border-t border-gray-100">
                    <div class="relative flex-1">
                        <i class="ti ti-receipt absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input id="paystack-reference" type="text" required placeholder="Paste payment reference here"
                               class="w-full rounded-xl border-2 border-gray-300 pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition">
                    </div>
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white text-sm font-semibold px-6 py-3 rounded-xl shadow-sm transition-all">
                        Verify payment
                    </button>
                </form>
                <p id="paystack-error" class="hidden text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-xl p-3"></p>
                <p id="paystack-success" class="hidden text-xs font-medium text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-xl p-3"></p>
            </div>

            <!-- ── Espees Panel ───────────────────────────────────────────────── -->
            <div data-pay-panel="espees" class="hidden space-y-6">
                <!-- Pricing Callout Box -->
                <div class="bg-gradient-to-br from-orange-50/60 to-orange-100/30 border border-orange-200/80 rounded-2xl p-5 text-center">
                    <span class="text-xs font-bold uppercase tracking-wider text-orange-600 bg-white px-2.5 py-1 rounded-full shadow-xs">Annual Access</span>
                    <div class="mt-3 flex items-baseline justify-center gap-1">
                        <span class="text-4xl font-extrabold text-gray-900">10 Espees</span>
                        <span class="text-sm font-medium text-gray-500">/ 1 year plan</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Secure blockchain/wallet verification.</p>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 border border-gray-200 rounded-2xl p-4">
                    <i class="ti ti-shield-lock text-orange-600 text-base mr-1.5 align-middle"></i>
                    You'll be redirected to the Espees payment portal to complete payment. Your wallet PIN is entered there securely and never captured on this site.
                </p>

                <button id="espees-checkout-btn" type="button" class="w-full inline-flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3.5 rounded-xl shadow-md hover:shadow-lg transition-all">
                    <i class="ti ti-wallet text-lg"></i>
                    Pay 10 Espees
                </button>

                <div id="espees-verify-box" class="hidden mt-4 pt-4 border-t border-gray-100 space-y-3">
                    <p class="text-sm font-medium text-gray-700">Completed payment on the Espees portal?</p>
                    <button id="espees-verify-btn" type="button" class="w-full border-2 border-orange-600 text-orange-700 hover:bg-orange-50 font-semibold px-6 py-3.5 rounded-xl transition-all">
                        I've paid — verify now
                    </button>
                </div>
                <p id="espees-error" class="hidden text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-xl p-3"></p>
                <p id="espees-success" class="hidden text-xs font-medium text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-xl p-3"></p>
            </div>
        </div>

        <!-- Transaction History Section -->
        <div class="bg-white rounded-3xl border-2 border-gray-200 shadow-sm p-6 sm:p-8 mb-8">
            <h2 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">
                <i class="ti ti-history text-orange-600"></i>
                Transaction history
            </h2>
            <div id="transactions-list" class="text-sm text-gray-600 space-y-2"></div>
            <p id="transactions-empty" class="hidden text-sm text-gray-400 italic py-2">No transactions recorded yet.</p>
        </div>

        <!-- Footer Navigation -->
        <div class="flex gap-3">
            <a href="/post-ad" class="inline-flex items-center gap-1.5 text-sm font-semibold text-orange-700 hover:text-orange-800 hover:underline">
                <i class="ti ti-arrow-left"></i> Back to Post an Ad
            </a>
        </div>
    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald956570c5321d7185b887a45463f814f)): ?>
<?php $attributes = $__attributesOriginald956570c5321d7185b887a45463f814f; ?>
<?php unset($__attributesOriginald956570c5321d7185b887a45463f814f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald956570c5321d7185b887a45463f814f)): ?>
<?php $component = $__componentOriginald956570c5321d7185b887a45463f814f; ?>
<?php unset($__componentOriginald956570c5321d7185b887a45463f814f); ?>
<?php endif; ?>
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/site/pricing.blade.php ENDPATH**/ ?>