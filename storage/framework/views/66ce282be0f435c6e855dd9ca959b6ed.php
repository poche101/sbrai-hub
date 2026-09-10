<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => 'Reset password — Sbrai Solutions','page' => 'auth']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Reset password — Sbrai Solutions','page' => 'auth']); ?>

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
    </style>

    <div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gray-50 px-4 sm:px-6 py-12">

        
        <div class="auth-bg-blob w-72 h-72 bg-orange-300 -top-10 -left-10"></div>
        <div class="auth-bg-blob w-80 h-80 bg-gray-900/10 bottom-0 -right-16" style="animation-delay: -3s;"></div>

        <div class="relative z-10 w-full max-w-md">

            <div class="auth-card bg-white rounded-3xl shadow-2xl shadow-gray-900/10 border border-gray-200 p-8 sm:p-10">

                <div class="flex flex-col items-center text-center mb-8">
                    <div class="auth-icon w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center mb-4 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-extrabold text-gray-950 tracking-tight">Reset Password</h1>
                    <p class="text-sm text-gray-500 mt-1">Enter your email and we'll send you a link to reset your password.</p>
                </div>

                <form id="forgot-password-form" class="space-y-4">
                    <p data-form-error class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl p-3"></p>
                    <p data-form-success class="hidden text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl p-3"></p>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" required placeholder="john@example.com"
                               class="auth-input w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-orange-600">
                    </div>

                    <button type="submit" class="auth-btn w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3.5 rounded-xl shadow-sm mt-2">
                        Send reset link
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Remembered your password?
                    <a href="<?php echo e(route('site.auth')); ?>" class="auth-link text-orange-600 font-semibold">Back to sign in</a>
                </p>
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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/site/forgot-password.blade.php ENDPATH**/ ?>