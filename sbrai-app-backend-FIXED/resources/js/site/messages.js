import { apiFetch, isLoggedIn } from './api';

function timeAgo(iso) {
    const diffMs = Date.now() - new Date(iso).getTime();
    const mins = Math.round(diffMs / 60000);
    if (mins < 1) return 'just now';
    if (mins < 60) return `${mins}m`;
    const hrs = Math.round(mins / 60);
    if (hrs < 24) return `${hrs}h`;
    return `${Math.round(hrs / 24)}d`;
}

export function initMessagesPage() {
    const root = document.getElementById('messages-page');
    if (!root) return;

    if (!isLoggedIn()) {
        window.location.href = `/auth?next=${encodeURIComponent('/messages')}`;
        return;
    }

    const listEl = document.getElementById('chat-list');
    const emptyList = document.getElementById('chat-list-empty');
    const threadEmpty = document.getElementById('thread-empty');
    const threadActive = document.getElementById('thread-active');
    const threadHeaderName = document.getElementById('thread-header-name');
    const threadHeaderListing = document.getElementById('thread-header-listing');
    const messagesEl = document.getElementById('thread-messages');
    const composeForm = document.getElementById('compose-form');
    const composeInput = document.getElementById('compose-input');
    const callBtn = document.getElementById('thread-call-btn');

    let activeChatId = root.dataset.openChat || null;
    let activeChat = null;
    let pollTimer = null;
    let listTimer = null;

    async function loadChatList() {
        try {
            const data = await apiFetch('/chats');
            const chats = data.data || [];
            emptyList.classList.toggle('hidden', chats.length > 0);
            listEl.innerHTML = chats
                .map(
                    (c) => `
                <button
                    data-chat-id="${c.id}"
                    class="w-full text-left p-3 rounded-md hover:bg-gray-50 flex gap-3 items-center ${c.id === activeChatId ? 'bg-emerald-50' : ''}"
                >
                    <div class="w-10 h-10 rounded-full bg-gray-200 shrink-0 overflow-hidden">
                        ${c.other_user_avatar ? `<img src="${c.other_user_avatar}" class="w-full h-full object-cover">` : ''}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex justify-between items-baseline">
                            <p class="font-medium text-sm text-gray-900 truncate">${c.other_user_name}</p>
                            <span class="text-xs text-gray-400 shrink-0">${timeAgo(c.last_message_time)}</span>
                        </div>
                        <p class="text-xs text-gray-500 truncate">${c.last_message || 'No messages yet'}</p>
                    </div>
                    ${c.unread_count > 0 ? `<span class="w-5 h-5 rounded-full bg-emerald-600 text-white text-xs flex items-center justify-center shrink-0">${c.unread_count}</span>` : ''}
                </button>`
                )
                .join('');

            return chats;
        } catch (err) {
            listEl.innerHTML = `<p class="text-sm text-red-500 p-3">${err.message}</p>`;
            return [];
        }
    }

    function renderMessages(messages, myUserId) {
        messagesEl.innerHTML = messages
            .map((m) => {
                const mine = m.sender_id === myUserId;
                return `
                <div class="flex ${mine ? 'justify-end' : 'justify-start'} mb-2">
                    <div class="max-w-[75%] rounded-lg px-3 py-2 text-sm ${mine ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-900'}">
                        ${m.image_url ? `<img src="${m.image_url}" class="rounded-md mb-1 max-w-full">` : ''}
                        <p>${m.content}</p>
                    </div>
                </div>`;
            })
            .join('');
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    async function openChat(chat) {
        activeChatId = chat.id;
        activeChat = chat;
        threadEmpty.classList.add('hidden');
        threadActive.classList.remove('hidden');
        threadHeaderName.textContent = chat.other_user_name;
        threadHeaderListing.textContent = chat.listing_title || '';

        await refreshThread();
        apiFetch(`/chats/${chat.id}/read`, { method: 'POST' }).catch(() => {});

        clearInterval(pollTimer);
        pollTimer = setInterval(refreshThread, 5000);

        await loadChatList();
    }

    async function refreshThread() {
        if (!activeChatId) return;
        try {
            const data = await apiFetch(`/chats/${activeChatId}/messages`);
            const meRes = await apiFetch('/auth/me');
            renderMessages(data.data || [], meRes.user.id);
        } catch (err) {
            // Non-fatal — keep showing whatever's already rendered.
        }
    }

    listEl.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-chat-id]');
        if (!btn) return;
        const chats = await loadChatList();
        const chat = chats.find((c) => c.id === btn.dataset.chatId);
        if (chat) openChat(chat);
    });

    composeForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const content = composeInput.value.trim();
        if (!content || !activeChatId) return;
        composeInput.value = '';
        try {
            await apiFetch(`/chats/${activeChatId}/messages`, { method: 'POST', json: { content } });
            refreshThread();
        } catch (err) {
            alert(err.message);
        }
    });

    callBtn?.addEventListener('click', () => {
        if (!activeChat) return;
        window.dispatchEvent(
            new CustomEvent('sbrai:start-call', {
                detail: { recipientId: activeChat.other_user_id, chatId: activeChatId, callType: 'voice' },
            })
        );
    });

    (async () => {
        const chats = await loadChatList();
        if (activeChatId) {
            const chat = chats.find((c) => c.id === activeChatId);
            if (chat) openChat(chat);
        }
        listTimer = setInterval(loadChatList, 10000);
    })();

    window.addEventListener('beforeunload', () => {
        clearInterval(pollTimer);
        clearInterval(listTimer);
    });
}
