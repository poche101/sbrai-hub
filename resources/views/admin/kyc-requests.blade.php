<x-admin-layout title="KYC Requests" subtitle="Review and approve vendor/buyer identity verifications">

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
    <h3 class="font-bold text-gray-900">Pending Verification</h3>
    <span class="text-xs font-semibold text-orange-500 bg-orange-50 px-2.5 py-1 rounded-full">
      {{ $pending->total() }} pending
    </span>
  </div>

  @if($pending->count() === 0)
    <div class="px-6 py-16 text-center">
      <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <p class="text-sm font-medium text-gray-500">No pending KYC requests</p>
      <p class="text-xs text-gray-400 mt-1">All caught up! New requests will appear here.</p>
    </div>
  @else
    <div class="divide-y divide-gray-50">
      @foreach($pending as $user)
      <div class="px-6 py-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 font-bold flex-shrink-0">
          {{ strtoupper(substr($user->full_name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2">
            <span class="font-semibold text-gray-900 text-sm">{{ $user->full_name }}</span>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $user->role === 'vendor' ? 'bg-orange-50 text-orange-600' : 'bg-navy-50 text-navy-500' }}">
              {{ ucfirst($user->role) }}
            </span>
          </div>
          <div class="text-xs text-gray-400 mt-0.5">{{ $user->email }} · {{ $user->phone }}</div>
          @if($user->role === 'vendor' && $user->cac_number)
            <div class="text-xs text-gray-400 mt-0.5">CAC: {{ $user->cac_number }}</div>
          @endif
          @if($user->identity_type)
            <div class="text-xs text-gray-400 mt-0.5">{{ strtoupper($user->identity_type) }}: {{ $user->identity_number }}</div>
          @endif
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
          <form method="POST" action="{{ route('admin.kyc.approve', $user->id) }}">
            @csrf
            <button type="submit" class="text-xs font-semibold px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition">Approve</button>
          </form>
          <button onclick="document.getElementById('reject-{{ $user->id }}').classList.toggle('hidden')" class="text-xs font-semibold px-4 py-2 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition">Reject</button>
        </div>
      </div>
      <div id="reject-{{ $user->id }}" class="hidden px-6 pb-4 bg-red-50/30">
        <form method="POST" action="{{ route('admin.kyc.reject', $user->id) }}" class="flex gap-2">
          @csrf
          <input type="text" name="reason" required placeholder="Reason for rejection..." class="flex-1 px-3 py-2 rounded-lg border border-red-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
          <button type="submit" class="text-xs font-semibold px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">Confirm Reject</button>
        </form>
      </div>
      @endforeach
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $pending->links() }}</div>
  @endif
</div>

</x-admin-layout>
