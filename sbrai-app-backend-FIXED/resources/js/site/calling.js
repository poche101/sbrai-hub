import { apiFetch, getUser } from './api';

let client = null;
let sdkLoadPromise = null;

function loadAgoraSdk() {
    if (window.AgoraRTC) return Promise.resolve();
    if (sdkLoadPromise) return sdkLoadPromise;
    sdkLoadPromise = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://download.agora.io/sdk/release/AgoraRTC_N-4.20.0.js';
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Could not load the calling library. Check your connection and try again.'));
        document.head.appendChild(script);
    });
    return sdkLoadPromise;
}

function buildCallUi() {
    if (document.getElementById('sbrai-call-overlay')) return;
    const overlay = document.createElement('div');
    overlay.id = 'sbrai-call-overlay';
    overlay.className = 'hidden fixed inset-0 bg-black/80 z-50 flex flex-col items-center justify-center gap-4 p-6';
    overlay.innerHTML = `
        <p id="sbrai-call-status" class="text-white text-sm">Connecting…</p>
        <div class="flex gap-4">
            <div id="sbrai-call-remote" class="w-64 h-48 bg-gray-800 rounded-lg overflow-hidden"></div>
        </div>
        <button id="sbrai-call-end" class="bg-red-600 text-white font-medium px-6 py-3 rounded-full hover:bg-red-700">
            End call
        </button>
    `;
    document.body.appendChild(overlay);
    document.getElementById('sbrai-call-end').addEventListener('click', endCall);
}

function setStatus(text) {
    const el = document.getElementById('sbrai-call-status');
    if (el) el.textContent = text;
}

function showOverlay() {
    document.getElementById('sbrai-call-overlay')?.classList.remove('hidden');
}
function hideOverlay() {
    document.getElementById('sbrai-call-overlay')?.classList.add('hidden');
}

async function endCall(recipientId, channelName) {
    hideOverlay();
    try {
        if (client) {
            await client.leave();
        }
    } catch (e) {
        // Already left / never joined — fine.
    }
    if (recipientId && channelName) {
        apiFetch('/calling/end', {
            method: 'POST',
            json: { recipient_id: recipientId, channel_name: channelName, reason: 'ended' },
        }).catch(() => {});
    }
}

async function startCall({ recipientId, chatId, callType }) {
    buildCallUi();
    showOverlay();
    setStatus('Setting up call…');

    const channelName = `chat-${chatId}`;
    const user = getUser();

    try {
        await loadAgoraSdk();

        const tokenRes = await apiFetch('/calling/token', {
            method: 'POST',
            json: { channel_name: channelName, uid: 0 },
        });

        await apiFetch('/calling/initiate', {
            method: 'POST',
            json: { recipient_id: recipientId, call_type: callType, channel_name: channelName },
        });

        client = window.AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });

        client.on('user-published', async (remoteUser, mediaType) => {
            await client.subscribe(remoteUser, mediaType);
            if (mediaType === 'video') {
                remoteUser.videoTrack.play('sbrai-call-remote');
            }
            if (mediaType === 'audio') {
                remoteUser.audioTrack.play();
            }
            setStatus('Connected');
        });

        client.on('user-left', () => {
            setStatus('The other person left the call.');
        });

        await client.join(tokenRes.app_id, tokenRes.channel_name, tokenRes.token, tokenRes.uid || null);

        const localAudioTrack = await window.AgoraRTC.createMicrophoneAudioTrack();
        const tracksToPublish = [localAudioTrack];

        if (callType === 'video') {
            const localVideoTrack = await window.AgoraRTC.createCameraVideoTrack();
            tracksToPublish.push(localVideoTrack);
        }

        await client.publish(tracksToPublish);
        setStatus(`Waiting for ${user?.full_name ? 'the other person' : 'them'} to join…`);

        document.getElementById('sbrai-call-end').onclick = () => endCall(recipientId, channelName);
    } catch (err) {
        setStatus(`${err.message || 'Could not start the call.'} (If this keeps happening, Agora calling likely isn't fully configured on the server yet.)`);
        setTimeout(hideOverlay, 4000);
    }
}

export function initCalling() {
    window.addEventListener('sbrai:start-call', (e) => startCall(e.detail));
}
