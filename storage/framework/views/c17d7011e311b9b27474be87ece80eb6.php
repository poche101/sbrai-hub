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

    <style>
        @keyframes card-in {
            from { opacity: 0; transform: translateY(24px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes icon-pop {
            0%   { opacity: 0; transform: scale(0.6) rotate(-8deg); }
            60%  { opacity: 1; transform: scale(1.08) rotate(2deg); }
            100% { opacity: 1; transform: scale(1) rotate(0deg); }
        }
        @keyframes float-blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%      { transform: translate(20px, -20px) scale(1.05); }
        }
        @keyframes panel-in {
            from { opacity: 0; transform: translateX(8px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes shake {
            10%, 90% { transform: translateX(-1px); }
            20%, 80% { transform: translateX(2px); }
            30%, 50%, 70% { transform: translateX(-4px); }
            40%, 60% { transform: translateX(4px); }
        }

        .auth-bg-blob {
            position: absolute;
            border-radius: 9999px;
            filter: blur(60px);
            opacity: 0.35;
            animation: float-blob 10s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }
        .auth-card {
            animation: card-in 0.55s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .auth-icon {
            animation: icon-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both;
        }
        [data-auth-panel]:not(.hidden) {
            animation: panel-in 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .auth-input {
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
        }
        .auth-input:hover {
            transform: translateY(-1px);
        }
        .auth-input:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.12), 0 4px 12px -2px rgba(0,0,0,0.08);
        }
        .auth-btn {
            position: relative;
            overflow: hidden;
            transition: transform 0.18s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease, background-color 0.2s ease;
        }
        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -8px rgba(234, 88, 12, 0.55);
        }
        .auth-btn:active {
            transform: translateY(0) scale(0.98);
        }
        .auth-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.25), transparent);
            transform: translateX(-120%);
            transition: transform 0.6s ease;
        }
        .auth-btn:hover::after {
            transform: translateX(120%);
        }
        [data-form-error]:not(.hidden) {
            animation: shake 0.45s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }
        .auth-link {
            position: relative;
        }
        .auth-link::after {
            content: '';
            position: absolute;
            left: 0; bottom: -2px;
            width: 100%; height: 1.5px;
            background: currentColor;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.25s ease;
        }
        .auth-link:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* ── Sliding segmented toggle (Buyer / Vendor) ─────────────── */
        .role-toggle {
            position: relative;
        }
        .role-toggle-indicator {
            position: absolute;
            top: 4px;
            bottom: 4px;
            left: 4px;
            width: calc(50% - 4px);
            border-radius: 0.625rem;
            background: #ea580c; /* orange-600 */
            box-shadow: 0 2px 8px -1px rgba(234, 88, 12, 0.5), 0 1px 2px rgba(0,0,0,0.06);
            transition: transform 0.32s cubic-bezier(0.65, 0, 0.35, 1), background-color 0.32s ease;
            z-index: 0;
        }
        .role-toggle-label {
            position: relative;
            z-index: 1;
            transition: color 0.25s ease, transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .role-toggle-label.is-active {
            color: #fff;
            transform: scale(1.02);
        }
        .role-toggle-label:not(.is-active) {
            color: #6b7280; /* gray-500 */
        }
        .role-toggle-label:not(.is-active):hover {
            color: #374151; /* gray-700 */
        }

        /* ── Password visibility toggle ────────────────────────────── */
        .password-field-wrap {
            position: relative;
        }
        .password-toggle-btn {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 2.25rem;
            height: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.625rem;
            color: #9ca3af; /* gray-400 */
            background: transparent;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .password-toggle-btn:hover {
            color: #ea580c;
            background: rgba(234, 88, 12, 0.08);
        }
        .password-toggle-icon-stack {
            position: relative;
            width: 1.125rem;
            height: 1.125rem;
        }
        .password-toggle-icon-stack svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            transition: opacity 0.18s ease, transform 0.18s ease;
        }
        .password-toggle-icon-stack svg[data-eye-open] {
            opacity: 1;
            transform: scale(1);
        }
        .password-toggle-icon-stack svg[data-eye-closed] {
            opacity: 0;
            transform: scale(0.6);
        }
        .password-toggle-btn.is-visible .password-toggle-icon-stack svg[data-eye-open] {
            opacity: 0;
            transform: scale(0.6);
        }
        .password-toggle-btn.is-visible .password-toggle-icon-stack svg[data-eye-closed] {
            opacity: 1;
            transform: scale(1);
        }
    </style>

    <div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gray-50 px-4 sm:px-6 py-12">

        
        <div class="auth-bg-blob w-72 h-72 bg-orange-300 -top-10 -left-10"></div>
        <div class="auth-bg-blob w-80 h-80 bg-gray-900/10 bottom-0 -right-16" style="animation-delay: -3s;"></div>

        <div class="relative z-10 w-full max-w-md">

            <div class="auth-card bg-white rounded-3xl shadow-2xl shadow-gray-900/10 border border-gray-200 p-8 sm:p-10">

                
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="auth-icon w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center mb-4 shadow-sm">
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

                        
                        <div class="role-toggle flex bg-gray-100 rounded-xl p-1 border border-gray-200 mb-2" data-toggle-group="login">
                            <div class="role-toggle-indicator" data-toggle-indicator></div>
                            <label class="flex-1">
                                <input type="radio" name="login_role" value="buyer" data-login-role-option class="sr-only" checked>
                                <span class="role-toggle-label is-active block text-center text-xs font-bold uppercase tracking-wider py-2.5 rounded-lg cursor-pointer">
                                    Buyer
                                </span>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="login_role" value="vendor" data-login-role-option class="sr-only">
                                <span class="role-toggle-label block text-center text-xs font-bold uppercase tracking-wider py-2.5 rounded-lg cursor-pointer">
                                    Vendor
                                </span>
                            </label>
                        </div>

                        
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
                                   class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Password</label>
                                <a href="<?php echo e(route('password.request')); ?>" class="auth-link text-xs font-semibold text-orange-600 normal-case tracking-normal">Forgot password?</a>
                            </div>
                            <div class="password-field-wrap">
                                <input type="password" name="password" required placeholder="Enter your password"
                                       class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 pr-12 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                                <button type="button" data-password-toggle tabindex="-1" aria-label="Show password"
                                        class="password-toggle-btn">
                                    <span class="password-toggle-icon-stack">
                                        <svg data-eye-open xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 11 7 11 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                            <path d="M6.61 6.61A13.53 13.53 0 0 0 1 12s4 7 11 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                            <line x1="1" y1="1" x2="23" y2="23"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="auth-btn w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3.5 rounded-xl shadow-sm mt-2">
                            Sign In
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-6">
                        Don't have an account?
                        <button type="button" data-auth-tab="register" class="auth-link text-orange-600 font-semibold">Create account</button>
                    </p>
                </div>

                
                <div data-auth-panel="register" class="hidden">
                    <form id="register-form" class="space-y-4">
                        <p data-form-error class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl p-3"></p>

                        
                        <div class="role-toggle flex bg-gray-100 rounded-xl p-1 border border-gray-200 mb-2" data-toggle-group="register">
                            <div class="role-toggle-indicator" data-toggle-indicator style="background:#111827; box-shadow:0 2px 8px -1px rgba(17,24,39,0.4), 0 1px 2px rgba(0,0,0,0.06);"></div>
                            <label class="flex-1">
                                <input type="radio" name="role" value="buyer" data-role-option class="sr-only" checked>
                                <span class="role-toggle-label is-active block text-center text-xs font-bold uppercase tracking-wider py-2.5 rounded-lg cursor-pointer">
                                    Buyer
                                </span>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="role" value="vendor" data-role-option class="sr-only">
                                <span class="role-toggle-label block text-center text-xs font-bold uppercase tracking-wider py-2.5 rounded-lg cursor-pointer">
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
                                   class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address</label>
                            <input type="email" name="email" required placeholder="john@example.com"
                                   class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Phone Number</label>
                            <input type="tel" name="phone" required placeholder="0801234567"
                                   class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Address <span class="text-gray-400 font-normal normal-case">(Optional)</span></label>
                            <input type="text" name="address" placeholder="Your location"
                                   class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                        </div>

                        
                        <div data-vendor-fields class="hidden space-y-4 border-t border-gray-100 pt-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Business Name</label>
                                <input type="text" name="business_name" placeholder="Your business name"
                                       class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Business Address <span class="text-gray-400 font-normal normal-case">(Optional)</span></label>
                                <input type="text" name="business_address" placeholder="Shop/office address"
                                       class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">CAC Number <span class="text-gray-400 font-normal normal-case">(optional now, needed later for KYC)</span></label>
                                <input type="text" name="cac_number" placeholder="RC1234567"
                                       class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                            <div class="password-field-wrap">
                                <input type="password" name="password" required minlength="6" placeholder="Create a password"
                                       class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 pr-12 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                                <button type="button" data-password-toggle tabindex="-1" aria-label="Show password"
                                        class="password-toggle-btn">
                                    <span class="password-toggle-icon-stack">
                                        <svg data-eye-open xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 11 7 11 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                            <path d="M6.61 6.61A13.53 13.53 0 0 0 1 12s4 7 11 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                            <line x1="1" y1="1" x2="23" y2="23"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Confirm Password</label>
                            <div class="password-field-wrap">
                                <input type="password" name="password_confirmation" required minlength="6" placeholder="Re-enter password"
                                       class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 pr-12 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                                <button type="button" data-password-toggle tabindex="-1" aria-label="Show password"
                                        class="password-toggle-btn">
                                    <span class="password-toggle-icon-stack">
                                        <svg data-eye-open xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 11 7 11 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                            <path d="M6.61 6.61A13.53 13.53 0 0 0 1 12s4 7 11 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                            <line x1="1" y1="1" x2="23" y2="23"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <label class="flex items-start gap-2.5 text-sm text-gray-600">
                            <input type="checkbox" name="agree_terms" required class="mt-0.5 h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                            <span>
                                I agree to the
                                <a href="/terms" target="_blank" class="auth-link text-orange-600 font-semibold">Terms &amp; Conditions</a>
                                and
                                <a href="/privacy" target="_blank" class="auth-link text-orange-600 font-semibold">Privacy Policy</a>.
                            </span>
                        </label>

                        <button type="submit" class="auth-btn w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3.5 rounded-xl shadow-sm mt-2">
                            Create Account
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-6">
                        Already have an account?
                        <button type="button" data-auth-tab="login" class="auth-link text-orange-600 font-semibold">Sign In</button>
                    </p>
                </div>
            </div>

            
            <div data-register-note class="hidden mt-6 bg-white/80 backdrop-blur border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="text-xs text-gray-500 leading-relaxed text-center">
                    After signing up you'll complete identity verification. Buyers must be verified before messaging; vendors must hold an active subscription.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const heading = document.querySelector('[data-heading]');
            const subheading = document.querySelector('[data-subheading]');
            const registerNote = document.querySelector('[data-register-note]');
            const roleOptions = document.querySelectorAll('[data-role-option]');
            const loginRoleOptions = document.querySelectorAll('[data-login-role-option]');
            const vendorFields = document.querySelector('[data-vendor-fields]');

            const copy = {
                login: {
                    buyer:  { heading: 'Sign In', subheading: 'Sign in to your buyer account' },
                    vendor: { heading: 'Sign In', subheading: 'Sign in to your vendor account' },
                },
                register: {
                    buyer:  { heading: 'Sign Up as Buyer',  subheading: 'Create your account to start buying' },
                    vendor: { heading: 'Sign Up as Vendor', subheading: 'Create your account to start selling' },
                },
            };

            function currentRole() {
                const checked = document.querySelector('[data-role-option]:checked');
                return checked ? checked.value : 'buyer';
            }

            function currentLoginRole() {
                const checked = document.querySelector('[data-login-role-option]:checked');
                return checked ? checked.value : 'buyer';
            }

            function renderHeading(mode) {
                if (mode === 'login') {
                    const role = currentLoginRole();
                    heading.textContent = copy.login[role].heading;
                    subheading.textContent = copy.login[role].subheading;
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

            // ── Sliding segmented toggle (Buyer / Vendor) ───────────────
            function updateToggle(container) {
                const options = container.querySelectorAll('input[type="radio"]');
                const indicator = container.querySelector('[data-toggle-indicator]');
                const labels = container.querySelectorAll('.role-toggle-label');
                const index = Array.from(options).findIndex(opt => opt.checked);

                indicator.style.transform = `translateX(${index * 100}%)`;

                labels.forEach((label, i) => {
                    label.classList.toggle('is-active', i === index);
                });

                // Register toggle: dark for buyer, orange for vendor.
                // Login toggle: orange for both (no dataset override present).
                if (container.dataset.toggleGroup === 'register') {
                    indicator.style.background = index === 1 ? '#ea580c' : '#111827';
                    indicator.style.boxShadow = index === 1
                        ? '0 2px 8px -1px rgba(234,88,12,0.5), 0 1px 2px rgba(0,0,0,0.06)'
                        : '0 2px 8px -1px rgba(17,24,39,0.4), 0 1px 2px rgba(0,0,0,0.06)';
                }
            }

            document.querySelectorAll('[data-toggle-group]').forEach(container => {
                updateToggle(container); // set correct initial position/color
                container.querySelectorAll('input[type="radio"]').forEach(input => {
                    input.addEventListener('change', () => updateToggle(container));
                });
            });

            roleOptions.forEach(input => {
                input.addEventListener('change', () => renderHeading('register'));
            });

            loginRoleOptions.forEach(input => {
                input.addEventListener('change', () => renderHeading('login'));
            });

            const mode = <?php echo json_encode($mode, 15, 512) ?>;
            openTab(mode === 'register' ? 'register' : 'login');

            // ── Password show/hide toggles ──────────────────────────
            document.querySelectorAll('[data-password-toggle]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const input = btn.closest('.password-field-wrap').querySelector('input');
                    const isHidden = input.type === 'password';

                    input.type = isHidden ? 'text' : 'password';
                    btn.classList.toggle('is-visible', isHidden);
                    btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                });
            });
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