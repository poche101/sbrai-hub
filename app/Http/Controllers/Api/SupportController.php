<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportConversation;
use App\Services\SupportAiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    public function start(Request $request): JsonResponse
    {
        $request->validate(['guest_token' => 'nullable|string']);

        $conversation = SupportConversation::create([
            'user_id' => $request->user()?->id,
            'guest_token' => $request->user() ? null : ($request->guest_token ?? (string) Str::uuid()),
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'guest_token' => $conversation->guest_token,
        ]);
    }

    public function sendMessage(Request $request, string $id, SupportAiService $ai): JsonResponse
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $conversation = SupportConversation::findOrFail($id);

        $conversation->messages()->create([
            'sender' => 'visitor',
            'body' => $request->message,
        ]);

        $result = $ai->reply($conversation, $request->message);

        $conversation->messages()->create([
            'sender' => 'ai',
            'body' => $result['reply'],
        ]);

        if ($result['escalate'] && $conversation->status !== 'escalated') {
            $conversation->update(['status' => 'escalated']);
            $this->notifyAdmin($conversation, $result['escalate_reason']);
        }

        return response()->json([
            'success' => true,
            'reply' => $result['reply'],
            'escalated' => $result['escalate'],
        ]);
    }

    public function history(Request $request, string $id): JsonResponse
    {
        $conversation = SupportConversation::with('messages')->findOrFail($id);

        return response()->json([
            'success' => true,
            'status' => $conversation->status,
            'messages' => $conversation->messages->map(fn ($m) => [
                'sender' => $m->sender,
                'body' => $m->body,
                'at' => $m->created_at->toISOString(),
            ]),
        ]);
    }

    protected function notifyAdmin(SupportConversation $conversation, string $reason): void
    {
        try {
            Mail::raw(
                "Conversation {$conversation->id} needs human follow-up.\nReason: {$reason}\n"
                . "View: " . url('/admin/support/' . $conversation->id),
                function ($message) {
                    $message->to(config('mail.support_admin_email', 'sbraisolutionshub@gmail.com'))
                        ->subject('Support conversation escalated');
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to notify admin of escalated support conversation: ' . $e->getMessage());
        }
    }
}
