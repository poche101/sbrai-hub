<x-admin-layout title="KYC Requests" subtitle="Review and approve vendor/buyer identity verifications">

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Pending Verification</h3>
            <span class="text-xs font-semibold text-orange-500 bg-orange-50 px-2.5 py-1 rounded-full">
                {{ $pending->total() }} pending
            </span>
        </div>

        @if ($pending->count() === 0)
            <div class="px-6 py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-500">No pending KYC requests</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach ($pending as $user)
                    @php
                        // Dynamically calculate the precise current field fill progress
                        $currentCount = 0;
                        if (!empty($user->email)) $currentCount++;
                        if (!empty($user->phone)) $currentCount++;
                        if (!empty($user->identity_number)) $currentCount++; // NIN field
                        if ($user->role === 'vendor' && !empty($user->cac_number)) $currentCount++;

                        $expectedCount = $user->getExpectedKycCount();
                    @endphp
                    <div class="px-6 py-6 hover:bg-gray-50/50 transition duration-150">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 font-bold flex-shrink-0 mt-0.5">
                                {{ strtoupper(substr($user->full_name, 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-gray-900 text-sm">{{ $user->full_name }}</span>

                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $user->role === 'vendor' ? 'bg-orange-50 text-orange-600' : 'bg-blue-50 text-blue-600' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>

                                    <!-- Exact Requirements Progress Badge Tracker -->
                                    @if($user->hasUploadedAllKycDocuments())
                                        <span class="text-[11px] font-medium bg-green-50 border border-green-200 text-green-700 px-2 py-0.5 rounded-md">
                                            Requirements Met ({{ $currentCount }}/{{ $expectedCount }})
                                        </span>
                                    @else
                                        <span class="text-[11px] font-medium bg-amber-50 border border-amber-200 text-amber-700 px-2 py-0.5 rounded-md">
                                            Incomplete Verification ({{ $currentCount }}/{{ $expectedCount }})
                                        </span>
                                    @endif
                                </div>

                                <div class="text-xs text-gray-500 mt-1 flex flex-wrap gap-x-2 gap-y-0.5">
                                    <span class="{{ empty($user->email) ? 'text-red-400 font-medium' : '' }}">
                                        Email: {{ $user->email ?? 'Missing' }}
                                    </span>
                                    <span class="text-gray-300">•</span>
                                    <span class="{{ empty($user->phone) ? 'text-red-400 font-medium' : '' }}">
                                        Phone: {{ $user->phone ?? 'Missing' }}
                                    </span>
                                </div>

                                <div class="mt-2 space-y-1 bg-gray-50 p-3 rounded-xl border border-gray-100/70 max-w-md">
                                    <div class="text-xs text-gray-600 flex justify-between">
                                        <span class="text-gray-400">NIN Status:</span>
                                        <span class="font-mono font-medium">{{ $user->identity_number ? $user->identity_number : '❌ Not Provided' }}</span>
                                    </div>
                                    @if ($user->role === 'vendor')
                                        <div class="text-xs text-gray-600 flex justify-between border-t border-gray-200/60 pt-1">
                                            <span class="text-gray-400">CAC Reg Number:</span>
                                            <span class="font-mono font-medium">{{ $user->cac_number ? $user->cac_number : '❌ Not Provided' }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Attached Files Engine Link Container -->
                                <div class="mt-4">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Verification Document Attachments</p>
                                    @if(is_array($user->kyc_documents) && count(array_filter($user->kyc_documents)) > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(array_filter($user->kyc_documents) as $key => $path)
                                                <a href="{{ str_starts_with($path, 'http') ? $path : Storage::url($path) }}"
                                                   target="_blank"
                                                   class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    {{ is_string($key) ? ucwords(str_replace('_', ' ', $key)) : 'View Attachment ' . ($loop->iteration) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-[11px] font-medium text-amber-600 bg-amber-50/50 inline-block px-2.5 py-1 rounded-md border border-amber-100">
                                            ℹ️ No direct verification media files uploaded to container.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0 self-start">
                                <form method="POST" action="/admin/kyc/{{ $user->id }}/approve">
                                    @csrf
                                    <button type="submit"
                                        {{ !$user->hasUploadedAllKycDocuments() ? 'disabled' : '' }}
                                        class="text-xs font-semibold px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-green-500">
                                        Approve
                                    </button>
                                </form>
                                <button
                                    onclick="document.getElementById('reject-{{ $user->id }}').classList.toggle('hidden')"
                                    class="text-xs font-semibold px-4 py-2 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition">
                                    Reject
                                </button>
                            </div>
                        </div>

                        <div id="reject-{{ $user->id }}" class="hidden mt-4 pl-15">
                            <form method="POST" action="{{ route('admin.kyc.reject', $user->id) }}" class="flex gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                @csrf
                                <input type="text" name="reason" required placeholder="Type reason for rejection..."
                                    class="flex-1 px-3 py-2 bg-white rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                                <button type="submit"
                                    class="text-xs font-semibold px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition shrink-0">
                                    Confirm Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $pending->links() }}</div>
        @endif
    </div>

</x-admin-layout>
