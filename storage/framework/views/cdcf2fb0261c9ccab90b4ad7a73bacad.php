<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => 'Terms & Conditions — Sbrai Solutions','page' => 'terms']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Terms & Conditions — Sbrai Solutions','page' => 'terms']); ?>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8 text-sm text-amber-800">
            <strong>Draft placeholder.</strong> This is generic starter text, not reviewed by a lawyer.
            Replace this with real terms drafted or reviewed by a qualified attorney before relying on it.
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Terms &amp; Conditions</h1>
        <p class="text-sm text-gray-500 mb-8">Last updated: <?php echo e(date('F Y')); ?></p>

        <div class="prose prose-sm max-w-none text-gray-700 space-y-6">
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">1. Using Sbrai Solutions</h2>
                <p>Sbrai Solutions is a marketplace connecting buyers with vendors of building materials, artisan services, and property listings across Nigeria. By creating an account, you agree to use the platform honestly and in accordance with these terms.</p>
            </section>
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">2. Accounts &amp; verification</h2>
                <p>Buyers and vendors must complete identity verification (email, phone, and government ID) before certain features — messaging, calling, and posting listings — become available. You're responsible for keeping your account credentials secure.</p>
            </section>
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">3. Vendor subscriptions</h2>
                <p>Vendors pay an annual subscription to post listings. Sbrai Solutions does not take a commission on sales — transactions happen directly between buyers and vendors, outside the platform.</p>
            </section>
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">4. Listings &amp; conduct</h2>
                <p>Listings must be accurate and lawful. Sbrai Solutions may remove listings or suspend accounts that violate these terms, misrepresent products or services, or are used for fraudulent activity.</p>
            </section>
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">5. Limitation of liability</h2>
                <p>Sbrai Solutions facilitates connections between buyers and vendors but is not a party to any transaction between them. We are not responsible for the quality, safety, or legality of listed items or services.</p>
            </section>
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">6. Changes to these terms</h2>
                <p>We may update these terms from time to time. Continued use of the platform after changes take effect constitutes acceptance of the updated terms.</p>
            </section>
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">7. Contact</h2>
                <p>Questions about these terms can be sent to <a href="mailto:support@sbraisolutions.com" class="text-orange-600 hover:underline">support@sbraisolutions.com</a>.</p>
            </section>
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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/site/terms.blade.php ENDPATH**/ ?>