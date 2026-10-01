<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => 'Favourites — Sbrai Solutions','page' => 'favourites']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Favourites — Sbrai Solutions','page' => 'favourites']); ?>

    <div id="favourites-page" class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-4" data-i18n="myFavorites">Favourites</h1>

        <p id="favourites-empty" class="hidden text-sm text-gray-400 py-10 text-center border border-dashed border-gray-200 rounded-2xl">
            No saved listings yet. <a href="/browse" class="text-orange-600 underline">Browse listings</a> and tap the heart to save one.
        </p>

        <div id="favourites-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4"></div>
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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/site/favourites.blade.php ENDPATH**/ ?>