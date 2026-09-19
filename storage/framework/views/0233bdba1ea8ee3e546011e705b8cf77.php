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

<h2>Verify your email</h2>

<p>
  Hi <?php echo e($name); ?>, use the code below to verify your email address on Sbrai Solutions.
</p>

<div style="text-align:center; margin: 24px 0;">
  <span style="display:inline-block; font-size:28px; font-weight:bold; letter-spacing:6px; color:#E8622A;">
    <?php echo e($otp); ?>

  </span>
</div>

<p style="font-size:13px; color:#999;">
  This code expires in 10 minutes. If you didn't request this, you can safely ignore this email.
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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/emails/otp.blade.php ENDPATH**/ ?>