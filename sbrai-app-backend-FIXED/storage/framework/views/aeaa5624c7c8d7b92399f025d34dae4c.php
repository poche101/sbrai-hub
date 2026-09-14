<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => 'Browse — Sbrai Solutions','page' => 'browse']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Browse — Sbrai Solutions','page' => 'browse']); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight" data-i18n="browse">Browse Listings</h1>
            <p id="browse-count" class="text-sm text-gray-500 mt-1">Loading…</p>
        </div>

        <form id="browse-filters" class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative flex items-center">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text" name="q" value="<?php echo e($initial['q']); ?>" placeholder="Search listings or vendors"
                        class="w-full pl-11 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 focus:outline-none transition bg-gray-50/50"
                    >
                </div>
                <select name="state" class="px-3 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 focus:outline-none transition bg-gray-50/50 cursor-pointer">
                    <option value="">All states</option>
                    <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($state); ?>" <?php if($initial['state'] === $state): echo 'selected'; endif; ?>><?php echo e($state); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="sort" class="px-3 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 focus:outline-none transition bg-gray-50/50 cursor-pointer">
                    <option value="recent">Newest</option>
                    <option value="popular">Most viewed</option>
                    <option value="price_asc">Price: low to high</option>
                    <option value="price_desc">Price: high to low</option>
                </select>
                <button type="button" id="browse-reset" class="flex items-center gap-2 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M10 18h4"/>
                    </svg>
                    Reset
                </button>
            </div>

            <input type="hidden" name="category" value="<?php echo e($initial['category']); ?>">
            <input type="hidden" name="type" value="<?php echo e($initial['type']); ?>">

            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
                <button
                    type="button" data-category-chip=""
                    class="sbrai-chip <?php echo e($initial['category'] === '' ? 'sbrai-chip-active' : ''); ?> px-4 py-2 rounded-full text-sm font-medium border border-gray-200"
                >All</button>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button
                        type="button" data-category-chip="<?php echo e($category->name); ?>"
                        class="sbrai-chip <?php echo e($initial['category'] === $category->name ? 'sbrai-chip-active' : ''); ?> px-4 py-2 rounded-full text-sm font-medium border border-gray-200"
                    ><?php echo e($category->name); ?></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </form>

        <!-- Empty State -->
        <p id="browse-empty" class="hidden text-sm text-gray-400 py-16 text-center border-2 border-dashed border-gray-200 rounded-2xl bg-white shadow-sm">
            No listings match those filters yet. Try checking your spelling or broadening your search.
        </p>

        <!-- Results Grid -->
        <div id="browse-results" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5"></div>

        <!-- Load More Section -->
        <div class="text-center mt-10">
            <button id="browse-load-more" class="hidden bg-white border border-gray-200 text-gray-700 font-semibold px-8 py-3 rounded-xl text-sm shadow-sm hover:bg-gray-50 hover:border-gray-300 transition">
                Load more listings
            </button>
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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/site/browse.blade.php ENDPATH**/ ?>