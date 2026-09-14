
<?php
    $active = $active ?? null;

    $categories = $categories ?? \App\Models\Category::query()
        ->when($listingType ?? null, fn ($query, $type) => $query->where('listing_type', $type))
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();
?>

<aside class="w-full sm:w-56 shrink-0 bg-white border border-gray-200 rounded-lg overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-900">All categories</h2>
    </div>

    <nav class="py-1 max-h-[520px] overflow-y-auto">
        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $isActive = $active === $category->name; ?>
            <a
                href="/browse?category=<?php echo e(urlencode($category->name)); ?>"
                class="group flex items-center justify-between gap-3 px-4 py-2.5 text-sm transition-colors
                       <?php echo e($isActive ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-orange-600'); ?>"
            >
                <span class="flex items-center gap-3 min-w-0">
                    <span class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-base shrink-0 overflow-hidden">
                        <?php if($category->image): ?>
                            <img src="<?php echo e(\Illuminate\Support\Facades\Storage::url($category->image)); ?>"
                                 alt="<?php echo e($category->name); ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <?php echo e($category->icon ?? '🏷️'); ?>

                        <?php endif; ?>
                    </span>
                    <span class="truncate"><?php echo e($category->name); ?></span>
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-gray-300 group-hover:text-orange-500 <?php echo e($isActive ? 'text-orange-500' : ''); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="px-4 py-3 text-sm text-gray-400">
                No categories yet.
            </p>
        <?php endif; ?>
    </nav>
</aside>
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/components/category-sidebar.blade.php ENDPATH**/ ?>