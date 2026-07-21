<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['title' => 'Users','subtitle' => 'Manage all platform users']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Users','subtitle' => 'Manage all platform users']); ?>

<div class="flex items-center justify-between mb-6">
  <form method="GET" class="flex gap-2">
    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name or email..."
      class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm w-72 focus:outline-none focus:ring-2 focus:ring-orange-500">
    <select name="role" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      <option value="">All Roles</option>
      <option value="buyer" <?php echo e(request('role') === 'buyer' ? 'selected' : ''); ?>>Buyers</option>
      <option value="vendor" <?php echo e(request('role') === 'vendor' ? 'selected' : ''); ?>>Vendors</option>
      <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>Admins</option>
    </select>
  </form>
  <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm hover:opacity-90 transition flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
    Add New User
  </button>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
  <table class="w-full text-sm">
    <thead>
      <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
        <th class="px-6 py-3.5">Name</th>
        <th class="px-6 py-3.5">Email</th>
        <th class="px-6 py-3.5">Type</th>
        <th class="px-6 py-3.5">KYC Status</th>
        <th class="px-6 py-3.5">Joined</th>
        <th class="px-6 py-3.5 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-50">
      <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr class="hover:bg-gray-50/50 transition">
        <td class="px-6 py-4">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs">
              <?php echo e(strtoupper(substr($user->full_name, 0, 1))); ?>

            </div>
            <span class="font-medium text-gray-900"><?php echo e($user->full_name); ?></span>
          </div>
        </td>
        <td class="px-6 py-4 text-gray-500"><?php echo e($user->email); ?></td>
        <td class="px-6 py-4">
          <span class="text-xs font-semibold px-2.5 py-1 rounded-full
            <?php echo e($user->role === 'vendor' ? 'bg-orange-50 text-orange-600' : ($user->role === 'admin' ? 'bg-navy-50 text-navy-600' : 'bg-blue-50 text-blue-600')); ?>">
            <?php echo e(ucfirst($user->role)); ?>

          </span>
        </td>
        <td class="px-6 py-4">
          <?php if($user->kyc_status === 'verified'): ?>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-50 text-green-600 flex items-center gap-1 w-fit">✓ Verified</span>
          <?php elseif($user->kyc_status === 'pending'): ?>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-600 w-fit">Pending</span>
          <?php elseif($user->kyc_status === 'rejected'): ?>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-red-50 text-red-600 w-fit">Rejected</span>
          <?php else: ?>
            <span class="text-xs text-gray-400">—</span>
          <?php endif; ?>
        </td>
        <td class="px-6 py-4 text-gray-400 text-xs"><?php echo e($user->created_at->format('d M Y')); ?></td>
        <td class="px-6 py-4 text-right">
          <?php if($user->role !== 'admin'): ?>
          <form method="POST" action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" onsubmit="return confirm('Delete this user?')" class="inline">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="text-red-400 hover:text-red-600 transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
  <div class="px-6 py-4 border-t border-gray-100"><?php echo e($users->links()); ?></div>
</div>


<div id="addUserModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl p-8 max-w-md w-full">
    <h3 class="font-bold text-lg text-gray-900 mb-5">Add New User</h3>
    <form method="POST" action="<?php echo e(route('admin.users.store')); ?>" class="space-y-4">
      <?php echo csrf_field(); ?>
      <input type="text" name="full_name" placeholder="Full Name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      <input type="email" name="email" placeholder="Email Address" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      <input type="text" name="phone" placeholder="Phone Number" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      <select name="role" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
        <option value="buyer">Buyer</option>
        <option value="vendor">Vendor</option>
        <option value="admin">Admin</option>
      </select>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="flex-1 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600">Cancel</button>
        <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-semibold">Create User</button>
      </div>
    </form>
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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/admin/users.blade.php ENDPATH**/ ?>