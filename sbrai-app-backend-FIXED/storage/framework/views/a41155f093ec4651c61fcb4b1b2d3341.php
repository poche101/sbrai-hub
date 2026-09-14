<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => 'My Profile — Sbrai Solutions','page' => 'profile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'My Profile — Sbrai Solutions','page' => 'profile']); ?>

    <div id="profile-page" class="max-w-3xl mx-auto px-4 sm:px-6 py-10 sm:py-14">

        
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                <svg width="22" height="22" class="h-[22px] w-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900" data-i18n="myProfile">My Profile</h1>
                <p class="text-sm text-gray-500">
                    Update your account details<span id="profile-role-note" class="hidden"> — vendor-specific fields appear below</span>.
                </p>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border border-gray-300 shadow-md shadow-gray-900/5 p-6 sm:p-8 mb-6">

            
            <div class="flex items-center gap-5 mb-8 pb-8 border-b border-gray-100">
                <div class="relative shrink-0">
                    <div id="avatar-preview" class="w-20 h-20 rounded-full bg-gradient-to-br from-orange-100 to-orange-50 ring-4 ring-white shadow-sm overflow-hidden flex items-center justify-center text-orange-400 text-2xl font-bold">
                        ?
                    </div>
                    <label class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-orange-600 text-white flex items-center justify-center cursor-pointer shadow-md ring-2 ring-white hover:bg-orange-700 transition-colors">
                        <svg width="14" height="14" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 112.828 2.828L11.828 13.828a4 4 0 01-1.414.94l-2.828.943.943-2.828a4 4 0 01.943-1.415z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 15v3a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2h3"/>
                        </svg>
                        <input id="avatar-input" type="file" accept="image/*" class="hidden">
                    </label>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Profile photo</p>
                    <p id="avatar-status" class="text-sm text-gray-500">JPG or PNG, at least 200×200px.</p>
                </div>
            </div>

            <form id="profile-form" class="space-y-5">
                <p id="profile-error" class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl p-3"></p>
                <p id="profile-success" class="hidden text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl p-3"></p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full name</label>
                        <input name="full_name" type="text" required placeholder="Your full name" class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] text-gray-900 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                        <input name="phone" type="tel" placeholder="0801 234 5678" class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] text-gray-900 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <div class="relative">
                        <input id="profile-email" type="email" disabled class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3.5 py-2.5 pr-9 text-[15px] text-gray-400">
                        <svg width="16" height="16" class="h-4 w-4 text-gray-300 absolute right-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">Email can't be changed here.</p>
                </div>

                <div id="vendor-fields" class="hidden space-y-5 border-t border-gray-100 pt-5">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-semibold uppercase tracking-wide text-orange-600">Vendor details</span>
                        <div class="h-px flex-1 bg-gray-100"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Business name</label>
                        <input name="business_name" type="text" placeholder="e.g. Sbrai Building Supplies" class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] text-gray-900 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Business address</label>
                        <input name="business_address" type="text" placeholder="Street, city, state" class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] text-gray-900 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3 rounded-lg shadow-sm transition-colors">
                        <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15 9.75m-3-7.036A11.955 11.955 0 013.598 6c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622a11.955 11.955 0 01-8.402-3.036 12.06 12.06 0 01-.599-.396c-.19-.129-.409-.129-.599 0z"/>
                        </svg>
                        Save changes
                    </button>
                </div>
            </form>
        </div>

        
        <div class="bg-white rounded-2xl border border-gray-300 shadow-md shadow-gray-900/5 p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center shrink-0">
                    <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-gray-900" data-i18n="changePassword">Change Password</h2>
            </div>

            <form id="password-form" class="space-y-5 max-w-sm">
                <p id="password-error" class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl p-3"></p>
                <p id="password-success" class="hidden text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl p-3"></p>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Current password</label>
                    <input name="current_password" type="password" required placeholder="••••••••" class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] text-gray-900 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">New password</label>
                    <input name="password" type="password" required minlength="6" placeholder="At least 6 characters" class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] text-gray-900 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm new password</label>
                    <input name="password_confirmation" type="password" required minlength="6" placeholder="Re-enter new password" class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] text-gray-900 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-colors">
                </div>

                <button type="submit" class="inline-flex items-center gap-2 border border-gray-300 font-semibold px-6 py-3 rounded-lg text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-400 transition-colors">
                    <svg width="16" height="16" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                    Update password
                </button>
            </form>
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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/site/profile.blade.php ENDPATH**/ ?>