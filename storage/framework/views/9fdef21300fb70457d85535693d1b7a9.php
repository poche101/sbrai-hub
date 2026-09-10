<?php if (isset($component)) { $__componentOriginal3287929725b3f878740bf3f25881b9ff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3287929725b3f878740bf3f25881b9ff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::layout'),'data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

<h2>Reset Your Password</h2>

<p>
  Hi <?php echo e($name); ?>, we received a request to reset the password for your
  Sbrai <?php echo e(ucfirst($role)); ?> account. Click the button below to choose a new password.
</p>

<div style="text-align:center">
  <a href="<?php echo e($resetUrl); ?>" class="btn btn-navy">Reset My Password</a>
</div>

<p style="font-size:13px; color:#999;">
  Or copy and paste this link into your browser:<br>
  <span style="color:#1A2B4A; word-break:break-all;"><?php echo e($resetUrl); ?></span>
</p>

<div class="divider"></div>

<p style="font-size:13px; color:#999;">
  This link expires in 60 minutes. If you didn't request a password reset,
  no action is needed — your account is safe.
</p>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3287929725b3f878740bf3f25881b9ff)): ?>
<?php $attributes = $__attributesOriginal3287929725b3f878740bf3f25881b9ff; ?>
<?php unset($__attributesOriginal3287929725b3f878740bf3f25881b9ff); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3287929725b3f878740bf3f25881b9ff)): ?>
<?php $component = $__componentOriginal3287929725b3f878740bf3f25881b9ff; ?>
<?php unset($__componentOriginal3287929725b3f878740bf3f25881b9ff); ?>
<?php endif; ?>
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/emails/reset-password.blade.php ENDPATH**/ ?>