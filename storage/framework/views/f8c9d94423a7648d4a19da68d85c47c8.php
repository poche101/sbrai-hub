<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['title' => 'Platform Listings','subtitle' => 'Monitor and manage products, services, and properties uploaded by vendors']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Platform Listings','subtitle' => 'Monitor and manage products, services, and properties uploaded by vendors']); ?>

<div class="space-y-6">
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="<?php echo e(route('admin.listings.index')); ?>" class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div class="relative w-full max-w-md">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search listings by title..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 bg-gray-50/50">
                <div class="absolute left-3.5 top-3.5 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="status" onchange="this.form.submit()" class="w-full sm:w-48 px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 bg-gray-50/50 text-gray-600">
                <option value="">All Statuses</option>
                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending Review</option>
                <option value="suspended" <?php echo e(request('status') === 'suspended' ? 'selected' : ''); ?>>Suspended</option>
            </select>

            <?php if(request('search') || request('status')): ?>
                <a href="<?php echo e(route('admin.listings.index')); ?>" class="text-xs font-semibold text-gray-400 hover:text-gray-600 underline">Clear Filters</a>
            <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $listings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900"><?php echo e($item->title); ?></div>
                            <div class="text-xs text-gray-400 mt-0.5">Created on <?php echo e($item->created_at->format('M d, Y')); ?> · Category: <?php echo e(ucfirst($item->category ?? 'General')); ?></div>
                        </td>

                        <td class="px-6 py-4">
                            <?php if($item->vendor): ?>
                                <div class="font-medium text-gray-800"><?php echo e($item->vendor->business_name ?? $item->vendor->full_name); ?></div>
                                <div class="text-xs text-gray-400 mt-0.5">ID: <?php echo e(substr($item->vendor->id, 0, 8)); ?>...</div>
                            <?php else: ?>
                                <span class="text-xs text-gray-400 italic">Unknown Vendor</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-900">
                            ₦<?php echo e(number_format($item->price, 2)); ?>

                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                <?php echo e($item->status === 'active' ? 'bg-green-50 text-green-700 border border-green-100' : ''); ?>

                                <?php echo e($item->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-100' : ''); ?>

                                <?php echo e($item->status === 'suspended' ? 'bg-red-50 text-red-700 border border-red-100' : ''); ?>

                            ">
                                <?php echo e(ucfirst($item->status)); ?>

                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <span class="text-xs text-gray-400 italic">Read-Only View</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            No listings match the database query filters.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($listings->hasPages()): ?>
            <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                <?php echo e($listings->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $attributes = $__attributesOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $component = $__componentOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__componentOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/admin/listings/index.blade.php ENDPATH**/ ?>