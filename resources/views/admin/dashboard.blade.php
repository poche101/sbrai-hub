<x-admin-layout title="Dashboard Overview" subtitle="Manage Sbrai Hub">

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6 sm:mb-8">

  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-6 0 4 4 0 016 0zm6 3a4 4 0 10-6 0"/></svg>
      </div>
      <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full whitespace-nowrap">+{{ $userGrowth->last()->count ?? 0 }} this wk</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_users']) }}</div>
    <div class="text-sm text-gray-400 mt-1">Total Users</div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
      </div>
      <span class="text-xs font-semibold text-gray-500 bg-gray-50 px-2 py-1 rounded-full whitespace-nowrap">{{ $stats['verified_vendors'] }} verified</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_vendors']) }}</div>
    <div class="text-sm text-gray-400 mt-1">Vendors</div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      </div>
      <span class="text-xs font-semibold text-gray-500 bg-gray-50 px-2 py-1 rounded-full whitespace-nowrap">{{ $stats['total_categories'] }} categories</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_listings']) }}</div>
    <div class="text-sm text-gray-400 mt-1">Total Listings</div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <span class="text-xs font-semibold text-orange-500 bg-orange-50 px-2 py-1 rounded-full whitespace-nowrap">{{ $stats['pending_kyc'] }} pending</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ number_format($stats['active_subs']) }}</div>
    <div class="text-sm text-gray-400 mt-1">Active Subscriptions</div>
  </div>
</div>

{{-- Revenue Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-6 sm:mb-8">
  <div class="rounded-2xl p-5 sm:p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #1A2B4A, #2D4A7A);">
    <div class="flex items-center justify-between mb-2">
      <span class="text-sm text-white/70">Total Revenue (Naira)</span>
      <svg class="w-5 h-5 text-white/50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold break-words">₦{{ number_format($stats['total_revenue_ngn']) }}</div>
    <div class="text-xs text-white/50 mt-2">From {{ $paymentSplit['stripe'] }} card subscriptions</div>
  </div>
  <div class="rounded-2xl p-5 sm:p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #E8622A, #C4501F);">
    <div class="flex items-center justify-between mb-2">
      <span class="text-sm text-white/70">Total Revenue (Espees)</span>
      <svg class="w-5 h-5 text-white/50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-5h8m-8 2h8M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold break-words">{{ number_format($stats['total_revenue_esp']) }} ESP</div>
    <div class="text-xs text-white/50 mt-2">From {{ $paymentSplit['espees'] }} Espees subscriptions</div>
  </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">

  {{-- Revenue Trend - gradient line chart --}}
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

  {{-- Payment method donut --}}
  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm min-w-0">
    <h3 class="font-bold text-gray-900 mb-1">Payment Methods</h3>
    <p class="text-xs text-gray-400 mb-4">Subscription split</p>
    <div class="relative w-full h-[200px] sm:h-[220px]">
      <canvas id="paymentChart"></canvas>
    </div>
    <div class="flex flex-wrap justify-center gap-4 sm:gap-6 mt-4 text-sm">
      <span class="flex items-center gap-2 whitespace-nowrap"><span class="w-3 h-3 rounded-full bg-orange-500"></span> Stripe ({{ $paymentSplit['stripe'] }})</span>
      <span class="flex items-center gap-2 whitespace-nowrap"><span class="w-3 h-3 rounded-full bg-navy-500"></span> Espees ({{ $paymentSplit['espees'] }})</span>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 mb-6 sm:mb-8">

  {{-- User growth bar chart --}}
  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm min-w-0">
    <h3 class="font-bold text-gray-900 mb-1">User Growth</h3>
    <p class="text-xs text-gray-400 mb-4">New signups per week (last 12 weeks)</p>
    <div class="relative w-full h-[200px] sm:h-[220px]">
      <canvas id="userGrowthChart"></canvas>
    </div>
  </div>

  {{-- Category distribution --}}
  <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 shadow-sm min-w-0">
    <h3 class="font-bold text-gray-900 mb-1">Top Categories</h3>
    <p class="text-xs text-gray-400 mb-4">By number of listings</p>
    <div class="relative w-full h-[200px] sm:h-[220px]">
      <canvas id="categoryChart"></canvas>
    </div>
  </div>
</div>

{{-- Activity Tables --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
      <h3 class="font-bold text-gray-900">Recent Users</h3>
      <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-orange-500 hover:underline whitespace-nowrap">View all →</a>
    </div>
    <div class="divide-y divide-gray-50 max-h-[340px] overflow-y-auto scrollbar-thin">
      @foreach($recentUsers as $u)
      <div class="px-4 sm:px-6 py-3.5 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-sm flex-shrink-0">
          {{ strtoupper(substr($u->full_name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-sm font-semibold text-gray-900 truncate">{{ $u->full_name }}</div>
          <div class="text-xs text-gray-400 truncate">{{ $u->email }}</div>
        </div>
        <span class="text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0
          {{ $u->role === 'vendor' ? 'bg-orange-50 text-orange-600' : 'bg-navy-50 text-navy-500' }}">
          {{ ucfirst($u->role) }}
        </span>
      </div>
      @endforeach
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
      <h3 class="font-bold text-gray-900">Recent Transactions</h3>
      <span class="text-xs text-gray-400 whitespace-nowrap">Last 8</span>
    </div>
    <div class="divide-y divide-gray-50 max-h-[340px] overflow-y-auto scrollbar-thin">
      @forelse($recentTransactions as $t)
      <div class="px-4 sm:px-6 py-3.5 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
          {{ $t->type === 'subscription' ? 'bg-green-50' : 'bg-gray-50' }}">
          <svg class="w-4 h-4 {{ $t->type === 'subscription' ? 'text-green-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-sm font-semibold text-gray-900 truncate">{{ $t->user->full_name ?? 'Unknown' }}</div>
          <div class="text-xs text-gray-400 truncate">{{ $t->description }}</div>
        </div>
        <div class="text-sm font-bold flex-shrink-0 {{ $t->currency === 'NGN' ? 'text-orange-500' : 'text-navy-500' }}">
          {{ $t->currency === 'NGN' ? '₦' . number_format($t->amount) : number_format($t->amount) . ' ESP' }}
        </div>
      </div>
      @empty
      <div class="px-4 sm:px-6 py-8 text-center text-sm text-gray-400">No transactions yet</div>
      @endforelse
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

  const trendData = @json(array_values($trendMap));
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
        data: [{{ $paymentSplit['stripe'] }}, {{ $paymentSplit['espees'] }}],
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

  const growthData = @json($userGrowth->pluck('count'));
  const growthLabels = @json($userGrowth->pluck('week_start'))
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
      labels: @json($categoryDistribution->pluck('category')),
      datasets: [{
        data: @json($categoryDistribution->pluck('count')),
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

</x-admin-layout>
