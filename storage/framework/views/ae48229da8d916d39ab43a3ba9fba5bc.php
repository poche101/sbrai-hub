<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['title' => 'Categories','subtitle' => 'Manage listing categories shown to vendors and buyers']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Categories','subtitle' => 'Manage listing categories shown to vendors and buyers']); ?>

    <?php if(session('success')): ?>
  <div class="mb-4 p-4 text-sm text-green-700 bg-green-50 rounded-xl border border-green-100">
      <?php echo e(session('success')); ?>

  </div>
<?php endif; ?>

<?php if(session('error') || $errors->any()): ?>
  <div class="mb-4 p-4 text-sm text-red-700 bg-red-50 rounded-xl border border-red-100">
      <?php echo e(session('error') ?? $errors->first()); ?>

  </div>
<?php endif; ?>

<div class="flex items-center justify-between mb-6">
  <p class="text-sm text-gray-500">Categories appear in the app's home screen and "Post Ad" flow. Only admins can create or edit them.</p>
  <a href="<?php echo e(route('admin.categories.create')); ?>" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm hover:opacity-90 transition flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Category
  </a>
</div>

<?php $__currentLoopData = ['product' => 'Products', 'service' => 'Services', 'property' => 'Properties']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
    <h3 class="font-bold text-gray-900"><?php echo e($label); ?></h3>
    <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">
      <?php echo e($categories->get($type, collect())->count()); ?> categories
    </span>
  </div>
  <div class="divide-y divide-gray-50">
    <?php $__empty_1 = true; $__currentLoopData = $categories->get($type, collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition">
      <div class="w-11 h-11 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-xl flex-shrink-0 overflow-hidden">
  <?php if($cat->image_url): ?>
    <img src="<?php echo e(asset($cat->image_url)); ?>" alt="<?php echo e($cat->name); ?>" class="w-full h-full object-cover">
  <?php else: ?>
    <?php echo e($cat->icon ?? '📦'); ?>

  <?php endif; ?>
</div>
      <div class="flex-1">
        <div class="flex items-center gap-2">
          <span class="font-semibold text-gray-900 text-sm"><?php echo e($cat->name); ?></span>
          <?php if(!$cat->is_active): ?>
            <span class="text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full font-medium">Inactive</span>
          <?php endif; ?>
        </div>
        <div class="text-xs text-gray-400 mt-0.5"><?php echo e($cat->listings_count); ?> listing(s) · slug: <?php echo e($cat->slug); ?></div>
      </div>
     <div class="flex items-center gap-2">

  <form method="POST" action="<?php echo e(route('admin.categories.toggle', ['category' => $cat])); ?>">
    <?php echo csrf_field(); ?>
    <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
      <?php echo e($cat->is_active ? 'Deactivate' : 'Activate'); ?>

    </button>
  </form>

  <a href= "<?php echo e(route('admin.categories.edit', $cat)); ?>" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Edit</a>

  <form method="POST" action="<?php echo e(route('admin.categories.destroy', ['category' => $cat])); ?>" onsubmit="return confirm('Delete this category?')">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
    <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 transition">Delete</button>
  </form>

</div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="px-6 py-8 text-center text-sm text-gray-400">No <?php echo e(strtolower($label)); ?> categories yet.</div>
    <?php endif; ?>
  </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>