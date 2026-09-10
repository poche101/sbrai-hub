<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['title' => 'Support Inbox']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Support Inbox']); ?>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Support Inbox</h1>

            <div class="flex gap-2 text-sm">
                <?php $__currentLoopData = ['all' => 'All', 'escalated' => 'Escalated', 'open' => 'Open', 'resolved' => 'Resolved']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.support.index', ['status' => $key])); ?>"
                       class="px-3 py-1.5 rounded-lg font-medium <?php echo e($status === $key ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'); ?>">
                        <?php echo e($label); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <?php if($conversations->isEmpty()): ?>
            <p class="text-gray-400 text-sm text-center py-16">No conversations yet.</p>
        <?php else: ?>
            <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100 overflow-hidden">
                <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $lastMessage = $conversation->messages->first(); ?>
                    <a href="<?php echo e(route('admin.support.show', $conversation->id)); ?>"
                       class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition">

                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-700 font-bold flex items-center justify-center shrink-0">
                            <?php echo e(strtoupper(substr($conversation->user->full_name ?? $conversation->guest_email ?? 'G', 0, 1))); ?>

                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-sm text-gray-900 truncate">
                                    <?php echo e($conversation->user->full_name ?? $conversation->guest_email ?? 'Guest visitor'); ?>

                                </p>
                                <?php if($conversation->status === 'escalated'): ?>
                                    <span class="text-[10px] font-bold uppercase tracking-wide bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Needs reply</span>
                                <?php elseif($conversation->status === 'resolved'): ?>
                                    <span class="text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Resolved</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-500 truncate">
                                <?php echo e($lastMessage ? \Illuminate\Support\Str::limit($lastMessage->body, 80) : '—'); ?>

                            </p>
                        </div>

                        <p class="text-xs text-gray-400 shrink-0"><?php echo e($conversation->updated_at->diffForHumans()); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-6">
                <?php echo e($conversations->links()); ?>

            </div>
        <?php endif; ?>

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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/admin/support/index.blade.php ENDPATH**/ ?>