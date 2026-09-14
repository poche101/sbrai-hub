<x-admin-layout title="Support Conversation">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.support.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to inbox</a>
                <h1 class="text-xl font-bold text-gray-900 mt-1">
                    {{ $conversation->user->full_name ?? $conversation->guest_email ?? 'Guest visitor' }}
                </h1>
                @if ($conversation->user)
                    <p class="text-xs text-gray-400">{{ $conversation->user->email }} &middot; {{ ucfirst($conversation->user->role) }}</p>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.support.download', $conversation->id) }}"
                   class="text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    Download
                </a>

                @if ($conversation->status !== 'resolved')
                    <form method="POST" action="{{ route('admin.support.resolve', $conversation->id) }}">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-green-700 bg-green-50 hover:bg-green-100 px-3 py-2 rounded-lg">
                            Mark resolved
                        </button>
                    </form>
                @else
                    <span class="text-sm font-semibold text-green-700 bg-green-50 px-3 py-2 rounded-lg">Resolved</span>
                @endif
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl p-3">
                {{ session('status') }}
            </div>
        @endif

        <div id="thread" class="bg-white rounded-2xl border border-gray-200 p-4 space-y-3 h-96 overflow-y-auto mb-4">
            @foreach ($conversation->messages as $message)
                <div class="flex {{ $message->sender === 'agent' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] rounded-2xl px-3 py-2 text-sm
                        {{ match($message->sender) {
                            'agent' => 'bg-orange-600 text-white rounded-br-sm',
                            'ai' => 'bg-gray-100 text-gray-800 rounded-bl-sm',
                            default => 'bg-blue-50 text-blue-900 rounded-bl-sm',
                        } }}">
                        <p class="text-[10px] uppercase tracking-wide opacity-60 mb-0.5">
                            {{ match($message->sender) { 'agent' => 'You', 'ai' => 'AI', default => 'Visitor' } }}
                        </p>
                        {{ $message->body }}
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('admin.support.reply', $conversation->id) }}" class="flex gap-2">
            @csrf
            <input type="text" name="message" required placeholder="Type a reply…"
                   class="flex-1 rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-orange-600">
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-5 py-3 rounded-xl">
                Send
            </button>
        </form>

    </div>

    <script>
        const conversationId = @json($conversation->id);
        const thread = document.getElementById('thread');

        async function poll() {
            try {
                const res = await fetch(`{{ url('/admin/support') }}/${conversationId}/messages`);
                const data = await res.json();

                thread.innerHTML = data.messages.map(m => {
                    const align = m.sender === 'agent' ? 'justify-end' : 'justify-start';
                    const bubble = m.sender === 'agent'
                        ? 'bg-orange-600 text-white rounded-br-sm'
                        : m.sender === 'ai'
                            ? 'bg-gray-100 text-gray-800 rounded-bl-sm'
                            : 'bg-blue-50 text-blue-900 rounded-bl-sm';
                    const label = m.sender === 'agent' ? 'You' : m.sender === 'ai' ? 'AI' : 'Visitor';
                    return `<div class="flex ${align}">
                        <div class="max-w-[75%] rounded-2xl px-3 py-2 text-sm ${bubble}">
                            <p class="text-[10px] uppercase tracking-wide opacity-60 mb-0.5">${label}</p>
                            ${m.body.replace(/</g, '&lt;')}
                        </div>
                    </div>`;
                }).join('');

                thread.scrollTop = thread.scrollHeight;
            } catch (e) { /* silent — next poll will retry */ }
        }

        setInterval(poll, 4000);
    </script>

</x-admin-layout>
