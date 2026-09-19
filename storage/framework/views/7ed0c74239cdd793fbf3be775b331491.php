<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['title' => 'Support Conversation']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Support Conversation']); ?>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="<?php echo e(route('admin.support.index')); ?>" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to inbox</a>
                <h1 class="text-xl font-bold text-gray-900 mt-1">
                    <?php echo e($conversation->user->full_name ?? $conversation->guest_email ?? 'Guest visitor'); ?>

                </h1>
                <?php if($conversation->user): ?>
                    <p class="text-xs text-gray-400"><?php echo e($conversation->user->email); ?> &middot; <?php echo e(ucfirst($conversation->user->role)); ?></p>
                <?php endif; ?>
            </div>

            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('admin.support.download', $conversation->id)); ?>"
                   class="text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    Download
                </a>

                <?php if($conversation->status !== 'resolved'): ?>
                    <form method="POST" action="<?php echo e(route('admin.support.resolve', $conversation->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-sm font-semibold text-green-700 bg-green-50 hover:bg-green-100 px-3 py-2 rounded-lg">
                            Mark resolved
                        </button>
                    </form>
                <?php else: ?>
                    <span class="text-sm font-semibold text-green-700 bg-green-50 px-3 py-2 rounded-lg">Resolved</span>
                <?php endif; ?>
            </div>
        </div>

        <?php if(session('status')): ?>
            <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl p-3">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <div id="thread" class="bg-white rounded-2xl border border-gray-200 p-4 space-y-3 h-96 overflow-y-auto mb-4">
            <?php $__currentLoopData = $conversation->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex <?php echo e($message->sender === 'agent' ? 'justify-end' : 'justify-start'); ?>">
                    <div class="max-w-[75%] rounded-2xl px-3 py-2 text-sm
                        <?php echo e(match($message->sender) {
                            'agent' => 'bg-orange-600 text-white rounded-br-sm',
                            'ai' => 'bg-gray-100 text-gray-800 rounded-bl-sm',
                            default => 'bg-blue-50 text-blue-900 rounded-bl-sm',
                        }); ?>">
                        <p class="text-[10px] uppercase tracking-wide opacity-60 mb-0.5">
                            <?php echo e(match($message->sender) { 'agent' => 'You', 'ai' => 'AI', default => 'Visitor' }); ?>

                        </p>
                        <?php echo e($message->body); ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <form method="POST" action="<?php echo e(route('admin.support.reply', $conversation->id)); ?>" class="flex gap-2">
            <?php echo csrf_field(); ?>
            <input type="text" name="message" required placeholder="Type a reply…"
                   class="flex-1 rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-orange-600">
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-5 py-3 rounded-xl">
                Send
            </button>
        </form>

    </div>

    <script>
        const conversationId = <?php echo json_encode($conversation->id, 15, 512) ?>;
        const thread = document.getElementById('thread');

        async function poll() {
            try {
                const res = await fetch(`<?php echo e(url('/admin/support')); ?>/${conversationId}/messages`);
                const data = await res.json();

                thread.innerHTML = data.messages.map(m => {
                    const align = m.sender === 'agent' ? 'justify-end' : 'justify-start';
                    const bubble = m.sender === 'agent'
                        ? 'bg-orange-600 text-white rounded-br-sm'
                        : m.sender === 'ai'
                            ? 'bg-gray-100 text-gray-800 rounded-bl-sm'
                            : 'bg-blue-50 text-blue-900 rounded-bl-sm';
                    const label = m.sender === 'agent' ? 'You' : m.sender === 'ai' ? 'AI' : 'Visitor';
                    return `<div class="flex ${align}">
                        <div class="max-w-[75%] rounded-2xl px-3 py-2 text-sm ${bubble}">
                            <p class="text-[10px] uppercase tracking-wide opacity-60 mb-0.5">${label}</p>
                            ${m.body.replace(/</g, '&lt;')}
                        </div>
                    </div>`;
                }).join('');

                thread.scrollTop = thread.scrollHeight;
            } catch (e) { /* silent — next poll will retry */ }
        }

        setInterval(poll, 4000);
    </script>

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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/admin/support/show.blade.php ENDPATH**/ ?>