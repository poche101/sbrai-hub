<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => 'Messages — Sbrai Solutions','page' => 'messages']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Messages — Sbrai Solutions','page' => 'messages']); ?>

    <div id="messages-page" data-open-chat="<?php echo e($openChatId); ?>" class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-xl font-semibold text-gray-900 mb-4">Messages</h1>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-0 border border-gray-200 rounded-lg overflow-hidden bg-white" style="min-height: 480px;">

            <div class="sm:col-span-1 border-r border-gray-200">
                <div id="chat-list" class="divide-y divide-gray-100 max-h-[480px] overflow-y-auto"></div>
                <p id="chat-list-empty" class="hidden text-sm text-gray-400 text-center p-6">
                    No conversations yet. Message a vendor from a listing page to start one.
                </p>
            </div>

            <div class="sm:col-span-2 flex flex-col">
                <p id="thread-empty" class="flex-1 flex items-center justify-center text-sm text-gray-400 p-6">
                    Select a conversation to view messages.
                </p>

                <div id="thread-active" class="hidden flex-1 flex flex-col">
                    <div class="border-b border-gray-100 p-3 flex items-center justify-between">
                        <div>
                            <p id="thread-header-name" class="font-medium text-gray-900 text-sm"></p>
                            <p id="thread-header-listing" class="text-xs text-gray-500"></p>
                        </div>
                        <button id="thread-call-btn" type="button" class="text-sm text-orange-700 font-medium hover:underline">
                            Call
                        </button>
                    </div>

                    <div id="thread-messages" class="flex-1 overflow-y-auto p-3" style="max-height: 380px;"></div>

                    <form id="compose-form" class="border-t border-gray-100 p-3 flex gap-2">
                        <input
                            id="compose-input" type="text" placeholder="Type a message…" required
                            class="flex-1 rounded-md border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500"
                        >
                        <button type="submit" class="bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-orange-700">
                            Send
                        </button>
                    </form>
                </div>
            </div>

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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/site/messages.blade.php ENDPATH**/ ?>