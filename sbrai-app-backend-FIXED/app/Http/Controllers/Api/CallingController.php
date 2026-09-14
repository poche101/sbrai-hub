<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AgoraTokenService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CallingController extends Controller
{
    /**
     * Generate Agora RTC token for a voice/video call.
     * Called by Flutter app before joining a channel.
     */
    public function generateToken(Request $request): JsonResponse
    {
        $request->validate([
            'channel_name' => 'required|string|max:64',
            'uid'          => 'nullable|integer',
        ]);

        $uid   = $request->uid ?? 0;
        $token = AgoraTokenService::generateToken($request->channel_name, $uid);

        return response()->json([
            'success'      => true,
            'token'        => $token,
            'channel_name' => $request->channel_name,
            'uid'          => $uid,
            'app_id'       => config('services.agora.app_id'),
            'expires_at'   => now()->addHour()->toISOString(),
        ]);
    }

    /**
     * Initiate a call - notify the recipient via FCM push.
     */
    public function initiateCall(Request $request): JsonResponse
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'call_type'    => 'required|in:voice,video',
            'channel_name' => 'required|string',
        ]);

        $caller    = $request->user();
        $recipient = User::findOrFail($request->recipient_id);
        $token     = AgoraTokenService::generateToken($request->channel_name, 0);

        NotificationService::sendPush(
            $recipient,
            $request->call_type === 'video' ? 'Incoming Video Call' : 'Incoming Voice Call',
            'From ' . ($caller->business_name ?? $caller->full_name),
            [
                'type'          => 'incoming_call',
                'call_type'     => $request->call_type,
                'channel_name'  => $request->channel_name,
                'caller_id'     => $caller->id,
                'caller_name'   => $caller->business_name ?? $caller->full_name,
                'caller_avatar' => $caller->avatar_url,
                'token'         => $token,
            ]
        );

        return response()->json([
            'success'      => true,
            'token'        => $token,
            'channel_name' => $request->channel_name,
        ]);
    }

    /**
     * End / decline a call - notify via FCM.
     */
    public function endCall(Request $request): JsonResponse
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'channel_name' => 'required|string',
            'reason'       => 'nullable|in:ended,declined,missed',
        ]);

        $recipient = User::findOrFail($request->recipient_id);

        NotificationService::sendPush(
            $recipient,
            'Call Ended',
            'The call has ended',
            [
                'type'         => 'call_ended',
                'channel_name' => $request->channel_name,
                'reason'       => $request->reason ?? 'ended',
            ]
        );

        return response()->json(['success' => true]);
    }
}
