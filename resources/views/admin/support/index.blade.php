<x-admin-layout title="Support Inbox">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Support Inbox</h1>

            <div class="flex gap-2 text-sm">
                @foreach (['all' => 'All', 'escalated' => 'Escalated', 'open' => 'Open', 'resolved' => 'Resolved'] as $key => $label)
                    <a href="{{ route('admin.support.index', ['status' => $key]) }}"
                       class="px-3 py-1.5 rounded-lg font-medium {{ $status === $key ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        @if ($conversations->isEmpty())
            <p class="text-gray-400 text-sm text-center py-16">No conversations yet.</p>
        @else
            <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100 overflow-hidden">
                @foreach ($conversations as $conversation)
                    @php $lastMessage = $conversation->messages->first(); @endphp
                    <a href="{{ route('admin.support.show', $conversation->id) }}"
                       class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition">

                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-700 font-bold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($conversation->user->full_name ?? $conversation->guest_email ?? 'G', 0, 1)) }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-sm text-gray-900 truncate">
                                    {{ $conversation->user->full_name ?? $conversation->guest_email ?? 'Guest visitor' }}
                                </p>
                                @if ($conversation->status === 'escalated')
                                    <span class="text-[10px] font-bold uppercase tracking-wide bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Needs reply</span>
                                @elseif ($conversation->status === 'resolved')
                                    <span class="text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Resolved</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 truncate">
                                {{ $lastMessage ? \Illuminate\Support\Str::limit($lastMessage->body, 80) : '—' }}
                            </p>
                        </div>

                        <p class="text-xs text-gray-400 shrink-0">{{ $conversation->updated_at->diffForHumans() }}</p>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $conversations->links() }}
            </div>
        @endif

    </div>

</x-admin-layout>
