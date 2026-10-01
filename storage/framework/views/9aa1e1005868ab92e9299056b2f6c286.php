<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['title' => 'Dashboard Overview','subtitle' => 'Manage Sbrai Hub']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dashboard Overview','subtitle' => 'Manage Sbrai Hub']); ?>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6 sm:mb-8">

  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-6 0 4 4 0 016 0zm6 3a4 4 0 10-6 0"/></svg>
      </div>
      <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full whitespace-nowrap">+<?php echo e($userGrowth->last()->count ?? 0); ?> this wk</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900"><?php echo e(number_format($stats['total_users'])); ?></div>
    <div class="text-sm text-gray-400 mt-1">Total Users</div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
      </div>
      <span class="text-xs font-semibold text-gray-500 bg-gray-50 px-2 py-1 rounded-full whitespace-nowrap"><?php echo e($stats['verified_vendors']); ?> verified</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900"><?php echo e(number_format($stats['total_vendors'])); ?></div>
    <div class="text-sm text-gray-400 mt-1">Vendors</div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      </div>
      <span class="text-xs font-semibold text-gray-500 bg-gray-50 px-2 py-1 rounded-full whitespace-nowrap"><?php echo e($stats['total_categories']); ?> categories</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900"><?php echo e(number_format($stats['total_listings'])); ?></div>
    <div class="text-sm text-gray-400 mt-1">Total Listings</div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <span class="text-xs font-semibold text-orange-500 bg-orange-50 px-2 py-1 rounded-full whitespace-nowrap"><?php echo e($stats['pending_kyc']); ?> pending</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900"><?php echo e(number_format($stats['active_subs'])); ?></div>
    <div class="text-sm text-gray-400 mt-1">Active Subscriptions</div>
  </div>
</div>


<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-6 sm:mb-8">
  <div class="rounded-2xl p-5 sm:p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #1A2B4A, #2D4A7A);">
    <div class="flex items-center justify-between mb-2">
      <span class="text-sm text-white/70">Total Revenue (Naira)</span>
      <svg class="w-5 h-5 text-white/50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold break-words">₦<?php echo e(number_format($stats['total_revenue_ngn'])); ?></div>
    <div class="text-xs text-white/50 mt-2">From <?php echo e($paymentSplit['stripe']); ?> card subscriptions</div>
  </div>
  <div class="rounded-2xl p-5 sm:p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #E8622A, #C4501F);">
    <div class="flex items-center justify-between mb-2">
      <span class="text-sm text-white/70">Total Revenue (Espees)</span>
      <svg class="w-5 h-5 text-white/50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-5h8m-8 2h8M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold break-words"><?php echo e(number_format($stats['total_revenue_esp'])); ?> ESP</div>
    <div class="text-xs text-white/50 mt-2">From <?php echo e($paymentSplit['espees']); ?> Espees subscriptions</div>
  </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">

  
  <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm min-w-0">
    <div class="flex flex-wrap items-start justify-between gap-2 mb-4">
      <div>
        <h3 class="font-bold text-gray-900">Revenue Trend</h3>
        <p class="text-xs text-gray-400">Last 30 days</p>
      </div>
      <div class="flex items-center gap-3 sm:gap-4 text-xs">
        <span class="flex items-center gap-1.5 whitespace-nowrap"><span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Naira (₦)</span>
        <span class="flex items-center gap-1.5 whitespace-nowrap"><span class="w-2.5 h-2.5 rounded-full bg-navy-500"></span> Espees</span>
      </div>
    </div>
    <div class="relative w-full h-[220px] sm:h-[260px] lg:h-[280px]">
      <canvas id="revenueChart"></canvas>
    </div>
  </div>

  
  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm min-w-0">
    <h3 class="font-bold text-gray-900 mb-1">Payment Methods</h3>
    <p class="text-xs text-gray-400 mb-4">Subscription split</p>
    <div class="relative w-full h-[200px] sm:h-[220px]">
      <canvas id="paymentChart"></canvas>
    </div>
    <div class="flex flex-wrap justify-center gap-4 sm:gap-6 mt-4 text-sm">
      <span class="flex items-center gap-2 whitespace-nowrap"><span class="w-3 h-3 rounded-full bg-orange-500"></span> Stripe (<?php echo e($paymentSplit['stripe']); ?>)</span>
      <span class="flex items-center gap-2 whitespace-nowrap"><span class="w-3 h-3 rounded-full bg-navy-500"></span> Espees (<?php echo e($paymentSplit['espees']); ?>)</span>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 mb-6 sm:mb-8">

  
  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm min-w-0">
    <h3 class="font-bold text-gray-900 mb-1">User Growth</h3>
    <p class="text-xs text-gray-400 mb-4">New signups per week (last 12 weeks)</p>
    <div class="relative w-full h-[200px] sm:h-[220px]">
      <canvas id="userGrowthChart"></canvas>
    </div>
  </div>

  
  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm min-w-0">
    <h3 class="font-bold text-gray-900 mb-1">Top Categories</h3>
    <p class="text-xs text-gray-400 mb-4">By number of listings</p>
    <div class="relative w-full h-[200px] sm:h-[220px]">
      <canvas id="categoryChart"></canvas>
    </div>
  </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
      <h3 class="font-bold text-gray-900">Recent Users</h3>
      <a href="<?php echo e(route('admin.users.index')); ?>" class="text-xs font-semibold text-orange-500 hover:underline whitespace-nowrap">View all →</a>
    </div>
    <div class="divide-y divide-gray-50 max-h-[340px] overflow-y-auto scrollbar-thin">
      <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="px-4 sm:px-6 py-3.5 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-sm flex-shrink-0">
          <?php echo e(strtoupper(substr($u->full_name, 0, 1))); ?>

        </div>
        <div class="flex-1 min-w-0">
          <div class="text-sm font-semibold text-gray-900 truncate"><?php echo e($u->full_name); ?></div>
          <div class="text-xs text-gray-400 truncate"><?php echo e($u->email); ?></div>
        </div>
        <span class="text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0
          <?php echo e($u->role === 'vendor' ? 'bg-orange-50 text-orange-600' : 'bg-navy-50 text-navy-500'); ?>">
          <?php echo e(ucfirst($u->role)); ?>

        </span>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
      <h3 class="font-bold text-gray-900">Recent Transactions</h3>
      <span class="text-xs text-gray-400 whitespace-nowrap">Last 8</span>
    </div>
    <div class="divide-y divide-gray-50 max-h-[340px] overflow-y-auto scrollbar-thin">
      <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="px-4 sm:px-6 py-3.5 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
          <?php echo e($t->type === 'subscription' ? 'bg-green-50' : 'bg-gray-50'); ?>">
          <svg class="w-4 h-4 <?php echo e($t->type === 'subscription' ? 'text-green-500' : 'text-gray-400'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-sm font-semibold text-gray-900 truncate"><?php echo e($t->user->full_name ?? 'Unknown'); ?></div>
          <div class="text-xs text-gray-400 truncate"><?php echo e($t->description); ?></div>
        </div>
        <div class="text-sm font-bold flex-shrink-0 <?php echo e($t->currency === 'NGN' ? 'text-orange-500' : 'text-navy-500'); ?>">
          <?php echo e($t->currency === 'NGN' ? '₦' . number_format($t->amount) : number_format($t->amount) . ' ESP'); ?>

        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="px-4 sm:px-6 py-8 text-center text-sm text-gray-400">No transactions yet</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  // Gradient helper
  function makeGradient(ctx, color1, color2) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, color1);
    gradient.addColorStop(1, color2);
    return gradient;
  }

  const trendData = <?php echo json_encode(array_values($trendMap), 15, 512) ?>;
  const labels = trendData.map(d => new Date(d.date).toLocaleDateString('en-GB', {day:'numeric', month:'short'}));

  // ─── Revenue Trend Chart (gradient area line) ───────────────────────
  const revCtx = document.getElementById('revenueChart').getContext('2d');
  const orangeGrad = makeGradient(revCtx, 'rgba(232,98,42,0.35)', 'rgba(232,98,42,0)');
  const navyGrad = makeGradient(revCtx, 'rgba(26,43,74,0.35)', 'rgba(26,43,74,0)');

  new Chart(revCtx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Naira (₦)',
          data: trendData.map(d => d.ngn),
          borderColor: '#E8622A',
          backgroundColor: orangeGrad,
          fill: true,
          tension: 0.4,
          pointRadius: 0,
          borderWidth: 2.5,
        },
        {
          label: 'Espees',
          data: trendData.map(d => d.espees * 1500), // scaled visually for comparison
          borderColor: '#1A2B4A',
          backgroundColor: navyGrad,
          fill: true,
          tension: 0.4,
          pointRadius: 0,
          borderWidth: 2.5,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 8, autoSkip: true } },
        y: { grid: { color: '#F5F5F5' }, ticks: { font: { size: 10 } } }
      }
    }
  });

  // ─── Payment Method Donut ────────────────────────────────────────────
  new Chart(document.getElementById('paymentChart'), {
    type: 'doughnut',
    data: {
      labels: ['Stripe', 'Espees'],
      datasets: [{
        data: [<?php echo e($paymentSplit['stripe']); ?>, <?php echo e($paymentSplit['espees']); ?>],
        backgroundColor: ['#E8622A', '#1A2B4A'],
        borderWidth: 0,
        hoverOffset: 6,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '70%',
      plugins: { legend: { display: false } }
    }
  });

  // ─── User Growth Bar Chart (gradient bars) ───────────────────────────
  const growthCtx = document.getElementById('userGrowthChart').getContext('2d');
  const growthGrad = makeGradient(growthCtx, '#E8622A', '#FFB37A');

  const growthData = <?php echo json_encode($userGrowth->pluck('count'), 15, 512) ?>;
  const growthLabels = <?php echo json_encode($userGrowth->pluck('week_start'), 15, 512) ?>
    .map(d => new Date(d).toLocaleDateString('en-GB', {day:'numeric', month:'short'}));

  new Chart(growthCtx, {
    type: 'bar',
    data: {
      labels: growthLabels,
      datasets: [{
        label: 'New Users',
        data: growthData,
        backgroundColor: growthGrad,
        borderRadius: 8,
        maxBarThickness: 28,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 10 }, autoSkip: true, maxRotation: 0 } },
        y: { grid: { color: '#F5F5F5' }, ticks: { font: { size: 10 }, precision: 0 } }
      }
    }
  });

  // ─── Category Distribution Horizontal Bar ────────────────────────────
  const catCtx = document.getElementById('categoryChart').getContext('2d');
  const catGrad = makeGradient(catCtx, '#1A2B4A', '#2D4A7A');

  new Chart(catCtx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($categoryDistribution->pluck('category'), 15, 512) ?>,
      datasets: [{
        data: <?php echo json_encode($categoryDistribution->pluck('count'), 15, 512) ?>,
        backgroundColor: catGrad,
        borderRadius: 8,
        maxBarThickness: 18,
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { color: '#F5F5F5' }, ticks: { font: { size: 10 }, precision: 0 } },
        y: { grid: { display: false }, ticks: { font: { size: 11 } } }
      }
    }
  });
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
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>