<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => 'Sign in — Sbrai Solutions','page' => 'auth']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Sign in — Sbrai Solutions','page' => 'auth']); ?>

    <div class="max-w-md mx-auto px-4 sm:px-6 py-12">

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-100 border border-gray-200 p-8 sm:p-10">

            
            <div class="flex flex-col items-center text-center mb-8">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center mb-4 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                        <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                        <path d="M2 7h20"/>
                        <path d="M22 7v3a2 2 0 0 1-2 2 2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"/>
                    </svg>
                </div>
                <h1 data-heading class="text-2xl font-extrabold text-gray-950 tracking-tight">Sign In</h1>
                <p data-subheading class="text-sm text-gray-500 mt-1">Sign in to your account</p>
            </div>

            
            <div data-auth-panel="login">
                <form id="login-form" class="space-y-4">
                    <p data-form-error class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl p-3"></p>

                    
                    <div class="space-y-3">
                        <div
                            id="google-signin-btn-login"
                            data-client-id="<?php echo e($googleClientId); ?>"
                            class="w-full flex justify-center"
                        ></div>
                        <?php if (! ($googleClientId)): ?>
                            <p class="text-xs text-gray-400 text-center">
                                Sign-in with Google isn't configured yet (set GOOGLE_CLIENT_ID in .env).
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center gap-3 py-2">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-[11px] tracking-wider text-gray-400 font-semibold uppercase">Or continue with email</span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" required placeholder="john@example.com"
                               class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required placeholder="Enter your password"
                               class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                    </div>

                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3.5 rounded-xl shadow-sm transition-all duration-200 active:scale-[0.99] mt-2">
                        Sign In
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Don't have an account?
                    <button type="button" data-auth-tab="register" class="text-orange-600 font-semibold hover:underline">Create account</button>
                </p>
            </div>

            
            <div data-auth-panel="register" class="hidden">
                <form id="register-form" class="space-y-4">
                    <p data-form-error class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl p-3"></p>

                    
                    <div class="flex bg-gray-100 rounded-xl p-1 border border-gray-200 mb-2">
                        <label class="flex-1">
                            <input type="radio" name="role" value="buyer" data-role-option class="peer sr-only" checked>
                            <span class="block text-center text-xs font-bold uppercase tracking-wider py-2.5 rounded-lg cursor-pointer text-gray-500 peer-checked:bg-white peer-checked:text-gray-950 peer-checked:shadow-sm transition">
                                Buyer
                            </span>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="role" value="vendor" data-role-option class="peer sr-only">
                            <span class="block text-center text-xs font-bold uppercase tracking-wider py-2.5 rounded-lg cursor-pointer text-gray-500 peer-checked:bg-white peer-checked:text-orange-600 peer-checked:shadow-sm transition">
                                Vendor
                            </span>
                        </label>
                    </div>

                    
                    <div class="space-y-3">
                        <div
                            id="google-signin-btn"
                            data-client-id="<?php echo e($googleClientId); ?>"
                            class="w-full flex justify-center"
                        ></div>
                        <?php if (! ($googleClientId)): ?>
                            <p class="text-xs text-gray-400 text-center">
                                Sign-in with Google isn't configured yet (set GOOGLE_CLIENT_ID in .env).
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center gap-3 py-2">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-[11px] tracking-wider text-gray-400 font-semibold uppercase">Or continue with email</span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" name="full_name" required placeholder="John Doe"
                               class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" required placeholder="john@example.com"
                               class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Phone Number</label>
                        <input type="tel" name="phone" required placeholder="0801234567"
                               class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Address <span class="text-gray-400 font-normal normal-case">(Optional)</span></label>
                        <input type="text" name="address" placeholder="Your location"
                               class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                    </div>

                    
                    <div data-vendor-fields class="hidden space-y-4 border-t border-gray-100 pt-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Business Name</label>
                            <input type="text" name="business_name" placeholder="Your business name"
                                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Business Address <span class="text-gray-400 font-normal normal-case">(Optional)</span></label>
                            <input type="text" name="business_address" placeholder="Shop/office address"
                                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">CAC Number <span class="text-gray-400 font-normal normal-case">(optional now, needed later for KYC)</span></label>
                            <input type="text" name="cac_number" placeholder="RC1234567"
                                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required minlength="6" placeholder="Create a password"
                               class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" required minlength="6" placeholder="Re-enter password"
                               class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 hover:border-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all duration-300">
                    </div>

                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3.5 rounded-xl shadow-sm transition-all duration-200 active:scale-[0.99] mt-2">
                        Create Account
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Already have an account?
                    <button type="button" data-auth-tab="login" class="text-orange-600 font-semibold hover:underline">Sign In</button>
                </p>
            </div>
        </div>

        
        <div data-register-note class="hidden mt-6 bg-white/80 border border-gray-200 rounded-2xl p-4 shadow-sm">
            <p class="text-xs text-gray-500 leading-relaxed text-center">
                After signing up you'll complete identity verification. Buyers must be verified before messaging; vendors must hold an active subscription.
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const heading = document.querySelector('[data-heading]');
            const subheading = document.querySelector('[data-subheading]');
            const registerNote = document.querySelector('[data-register-note]');
            const roleOptions = document.querySelectorAll('[data-role-option]');
            const vendorFields = document.querySelector('[data-vendor-fields]');

            const copy = {
                login: {
                    heading: 'Sign In',
                    subheading: 'Sign in to your account',
                },
                register: {
                    buyer: {
                        heading: 'Sign Up as Buyer',
                        subheading: 'Create your account to start buying',
                    },
                    vendor: {
                        heading: 'Sign Up as Vendor',
                        subheading: 'Create your account to start selling',
                    },
                },
            };

            function currentRole() {
                const checked = document.querySelector('[data-role-option]:checked');
                return checked ? checked.value : 'buyer';
            }

            function renderHeading(mode) {
                if (mode === 'login') {
                    heading.textContent = copy.login.heading;
                    subheading.textContent = copy.login.subheading;
                    registerNote.classList.add('hidden');
                } else {
                    const role = currentRole();
                    heading.textContent = copy.register[role].heading;
                    subheading.textContent = copy.register[role].subheading;
                    registerNote.classList.remove('hidden');
                    vendorFields.classList.toggle('hidden', role !== 'vendor');
                }
            }

            function openTab(mode) {
                document.querySelectorAll('[data-auth-panel]').forEach(panel => {
                    panel.classList.toggle('hidden', panel.dataset.authPanel !== mode);
                });
                renderHeading(mode);
            }

            document.querySelectorAll('[data-auth-tab]').forEach(btn => {
                btn.addEventListener('click', () => openTab(btn.dataset.authTab));
            });

            roleOptions.forEach(input => {
                input.addEventListener('change', () => renderHeading('register'));
            });

            const mode = <?php echo json_encode($mode, 15, 512) ?>;
            openTab(mode === 'register' ? 'register' : 'login');
        });
    </script>

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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/site/auth.blade.php ENDPATH**/ ?>