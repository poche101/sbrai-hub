<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => $listing->title . ' — Sbrai Solutions','page' => 'listing']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($listing->title . ' — Sbrai Solutions'),'page' => 'listing']); ?>

    <div id="listing-page" data-listing-id="<?php echo e($listing->id); ?>" data-vendor-id="<?php echo e($listing->vendor_id); ?>" class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2">
                <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden mb-3">
                    <?php if(!empty($listing->image_urls[0])): ?>
                        <img id="main-photo" src="<?php echo e($listing->image_urls[0]); ?>" alt="<?php echo e($listing->title); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-300">No photos yet</div>
                    <?php endif; ?>
                </div>

                <?php if(count($listing->image_urls ?? []) > 1): ?>
                    <div class="flex gap-2 mb-6">
                        <?php $__currentLoopData = $listing->image_urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button
                                type="button"
                                onclick="document.getElementById('main-photo').src = '<?php echo e($url); ?>'"
                                class="w-16 h-16 rounded-md overflow-hidden border border-gray-200"
                            >
                                <img src="<?php echo e($url); ?>" class="w-full h-full object-cover">
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <h1 class="text-2xl font-bold text-gray-900"><?php echo e($listing->title); ?></h1>
                <p class="text-gray-500 mt-1"><?php echo e($listing->location); ?>, <?php echo e($listing->state); ?></p>
                <p class="text-2xl font-bold text-orange-700 mt-4">
                    ₦<?php echo e(number_format($listing->price)); ?>

                    <span class="text-sm font-normal text-gray-500">/ <?php echo e($listing->price_unit); ?></span>
                </p>

                <?php if($listing->type === 'property' && !empty($listing->attributes)): ?>
                    <div class="flex gap-6 mt-4 text-sm text-gray-700">
                        <?php if(!empty($listing->attributes['bedrooms'])): ?>
                            <span>🛏️ <?php echo e($listing->attributes['bedrooms']); ?> bed</span>
                        <?php endif; ?>
                        <?php if(!empty($listing->attributes['bathrooms'])): ?>
                            <span>🛁 <?php echo e($listing->attributes['bathrooms']); ?> bath</span>
                        <?php endif; ?>
                        <?php if(!empty($listing->attributes['furnishing'])): ?>
                            <span>🛋️ <?php echo e(ucfirst($listing->attributes['furnishing'])); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <h2 class="font-semibold text-gray-900 mt-6 mb-2">Description</h2>
                <p class="text-gray-700 whitespace-pre-line"><?php echo e($listing->description); ?></p>
            </div>

            <div>
                <div class="border border-gray-200 rounded-lg p-4 bg-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900 flex items-center gap-1.5">
                                <?php echo e($listing->vendor->business_name ?? $listing->vendor->full_name); ?>

                                <?php if($listing->vendor->is_online): ?>
                                    <span class="w-2 h-2 rounded-full bg-green-500" title="Online now"></span>
                                <?php endif; ?>
                            </p>
                            <?php if($listing->vendor->is_verified): ?>
                                <p class="text-xs text-green-600 mt-0.5">✓ Verified vendor</p>
                            <?php else: ?>
                                <p class="text-xs text-gray-400 mt-0.5">Not yet verified</p>
                            <?php endif; ?>
                            <?php if($listing->vendor->privacyAllows('show_phone') && $listing->vendor->phone): ?>
                                <p class="text-xs text-gray-500 mt-0.5">📞 <?php echo e($listing->vendor->phone); ?></p>
                            <?php endif; ?>
                        </div>
                        <button id="fav-btn" type="button" class="sbrai-fav-btn w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center" aria-label="Save listing">♥</button>
                    </div>

                    <?php if($listing->vendor->privacyAllows('allow_messages')): ?>
                        <div class="mt-4 space-y-2">
                            <button id="chat-btn" type="button" class="w-full bg-orange-600 text-white font-medium py-2.5 rounded-md hover:bg-orange-700">
                                Chat with vendor
                            </button>
                            <button id="call-btn" type="button" class="w-full border border-orange-600 text-orange-700 font-medium py-2.5 rounded-md hover:bg-orange-50">
                                Voice / video call
                            </button>
                        </div>
                    <?php else: ?>
                        <p class="mt-4 text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-md p-2.5">
                            This vendor is not accepting new messages right now.
                        </p>
                    <?php endif; ?>

                    <p id="contact-gate-note" class="hidden mt-3 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-md p-2"></p>

                    <div id="chat-compose" class="hidden mt-3">
                        <form id="chat-compose-form">
                            <p id="chat-compose-error" class="hidden text-xs text-red-600 bg-red-50 border border-red-200 rounded-md p-2 mb-2"></p>
                            <textarea
                                id="chat-compose-input" rows="2" required maxlength="1000"
                                placeholder="Say hello and ask your question…"
                                class="w-full text-sm rounded-md border-gray-300 focus:ring-orange-500 focus:border-orange-500"
                            ></textarea>
                            <button type="submit" class="mt-2 w-full bg-orange-600 text-white text-sm font-medium py-2 rounded-md hover:bg-orange-700">
                                Send message
                            </button>
                        </form>
                    </div>
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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/site/listing-show.blade.php ENDPATH**/ ?>