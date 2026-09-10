<div x-data="supportWidget()" x-init="init()" x-cloak
     class="fixed bottom-5 right-5 z-50"
     style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">

    
    <button @click="open = !open"
            class="w-14 h-14 rounded-full bg-orange-600 hover:bg-orange-700 shadow-lg flex items-center justify-center text-white transition-transform hover:scale-105"
            x-show="!open">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
        </svg>
    </button>

    
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="w-80 sm:w-96 h-[28rem] bg-white rounded-2xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden">

        <div class="bg-orange-600 text-white px-4 py-3 flex items-center justify-between">
            <div>
                <p class="font-bold text-sm">Emmy</p>
                <p class="text-xs text-orange-100" x-show="escalated">A team member has been notified and will follow up</p>
                <p class="text-xs text-orange-100" x-show="!escalated">Ask us anything</p>
            </div>
            <button @click="open = false" class="text-white/80 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-3 py-3 space-y-2" x-ref="scrollArea">
            <template x-for="(m, i) in messages" :key="i">
                <div :class="m.sender === 'visitor' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="m.sender === 'visitor'
                            ? 'bg-orange-600 text-white rounded-2xl rounded-br-sm px-3 py-2 text-sm max-w-[80%]'
                            : 'bg-gray-100 text-gray-800 rounded-2xl rounded-bl-sm px-3 py-2 text-sm max-w-[80%]'"
                         x-text="m.body"></div>
                </div>
            </template>
            <div x-show="loading" class="flex justify-start">
                <div class="bg-gray-100 rounded-2xl rounded-bl-sm px-3 py-2 text-sm text-gray-400">Typing…</div>
            </div>
        </div>

        <form @submit.prevent="send" class="border-t border-gray-100 p-2 flex gap-2">
            <input x-model="draft" type="text" placeholder="Type a message…"
                   class="flex-1 rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-orange-600">
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white rounded-xl px-3 py-2 text-sm font-semibold">
                Send
            </button>
        </form>
    </div>
</div>

<script>
function supportWidget() {
    return {
        open: false,
        loading: false,
        escalated: false,
        conversationId: null,
        draft: '',
        messages: [
            { sender: 'ai', body: "Hi! I'm the Sbrai support assistant. How can I help you today?" }
        ],

        async init() {
            let guestToken = localStorage.getItem('sbrai_support_token');
            const res = await fetch('/api/v1/support/conversations', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ guest_token: guestToken }),
            });
            const data = await res.json();
            this.conversationId = data.conversation_id;
            if (data.guest_token) localStorage.setItem('sbrai_support_token', data.guest_token);
        },

        async send() {
            const text = this.draft.trim();
            if (!text || this.loading) return;
            this.messages.push({ sender: 'visitor', body: text });
            this.draft = '';
            this.loading = true;
            this.scrollDown();

            try {
                const res = await fetch(`/api/v1/support/conversations/${this.conversationId}/messages`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: text }),
                });
                const data = await res.json();
                this.messages.push({ sender: 'ai', body: data.reply });
                this.escalated = data.escalated;
            } catch (e) {
                this.messages.push({ sender: 'ai', body: "Sorry, something went wrong. Please try again." });
            } finally {
                this.loading = false;
                this.scrollDown();
            }
        },

        scrollDown() {
            this.$nextTick(() => {
                this.$refs.scrollArea.scrollTop = this.$refs.scrollArea.scrollHeight;
            });
        }
    }
}
</script>
<?php /**PATH C:\Users\kings\Downloads\sbrai-app-backend-FIXED\resources\views/components/support-widget.blade.php ENDPATH**/ ?>