<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $chats = Chat::where(function ($q) use ($request) {
                $q->where('buyer_id', $request->user()->id)
                  ->orWhere('vendor_id', $request->user()->id);
            })
            ->with(['listing:id,title,image_urls', 'lastMessage', 'buyer:id,full_name,avatar_url', 'vendor:id,full_name,business_name,avatar_url'])
            ->orderByDesc('updated_at')
            ->get();

        $userId = $request->user()->id;

        return response()->json([
            'success' => true,
            'data'    => $chats->map(function ($chat) use ($userId) {
                $isVendor = $chat->vendor_id === $userId;
                $other    = $isVendor ? $chat->buyer : $chat->vendor;
                return [
                    'id'                    => $chat->id,
                    'other_user_id'         => $other->id,
                    'other_user_name'       => $other->business_name ?? $other->full_name,
                    'other_user_avatar'     => $other->avatar_url,
                    'other_user_is_vendor'  => !$isVendor,
                    'listing_id'            => $chat->listing_id,
                    'listing_title'         => $chat->listing->title ?? null,
                    'listing_image_url'     => $chat->listing?->image_urls[0] ?? null,
                    'last_message'          => $chat->lastMessage?->content ?? '',
                    'last_message_time'     => $chat->lastMessage?->created_at->toISOString() ?? $chat->created_at->toISOString(),
                    'unread_count'          => $chat->messages()
                        ->where('sender_id', '!=', $userId)
                        ->where('is_read', false)
                        ->count(),
                    'is_online'             => false,
                ];
            }),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'vendor_id'  => 'required|exists:users,id',
            'listing_id' => 'required|exists:listings,id',
            'message'    => 'required|string|max:1000',
        ]);

        // Find or create chat
        $chat = Chat::firstOrCreate([
            'buyer_id'   => $request->user()->id,
            'vendor_id'  => $request->vendor_id,
            'listing_id' => $request->listing_id,
        ]);

        // Send first message
        $message = $chat->messages()->create([
            'sender_id' => $request->user()->id,
            'content'   => $request->message,
        ]);

        // Notify vendor
        $vendor = User::find($request->vendor_id);
        NotificationService::sendPush($vendor, 'New Message', $request->message);

        return response()->json(['success' => true, 'chat' => ['id' => $chat->id]], 201);
    }

    public function messages(Request $request, string $chatId): JsonResponse
    {
        $chat = Chat::where(function ($q) use ($request) {
            $q->where('buyer_id', $request->user()->id)
              ->orWhere('vendor_id', $request->user()->id);
        })->findOrFail($chatId);

        $messages = $chat->messages()
            ->orderBy('created_at')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data'    => $messages->map(fn($m) => [
                'id'         => $m->id,
                'chat_id'    => $m->chat_id,
                'sender_id'  => $m->sender_id,
                'content'    => $m->content,
                'created_at' => $m->created_at->toISOString(),
                'is_read'    => $m->is_read,
                'image_url'  => $m->image_url,
            ]),
        ]);
    }

    public function sendMessage(Request $request, string $chatId): JsonResponse
    {
        $request->validate(['content' => 'required|string|max:2000']);

        $chat = Chat::where(function ($q) use ($request) {
            $q->where('buyer_id', $request->user()->id)
              ->orWhere('vendor_id', $request->user()->id);
        })->findOrFail($chatId);

        $message = $chat->messages()->create([
            'sender_id' => $request->user()->id,
            'content'   => $request->content,
        ]);

        $chat->touch(); // Update updated_at for ordering

        // Notify recipient
        $recipientId = $chat->buyer_id === $request->user()->id ? $chat->vendor_id : $chat->buyer_id;
        $recipient   = User::find($recipientId);
        if ($recipient) {
            NotificationService::sendPush($recipient, 'New Message', $request->content);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id'         => $message->id,
                'chat_id'    => $message->chat_id,
                'sender_id'  => $message->sender_id,
                'content'    => $message->content,
                'created_at' => $message->created_at->toISOString(),
                'is_read'    => false,
            ],
        ]);
    }

    public function markRead(Request $request, string $chatId): JsonResponse
    {
        Chat::findOrFail($chatId)
            ->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, string $chatId): JsonResponse
    {
        Chat::where(function ($q) use ($request) {
            $q->where('buyer_id', $request->user()->id)
              ->orWhere('vendor_id', $request->user()->id);
        })->findOrFail($chatId)->delete();
        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request, string $chatId): JsonResponse
    {
        $request->validate(['image' => 'required|image|max:5120']);
        $path = $request->file('image')->store("chats/{$chatId}", 'public');
        $url  = '/storage/' . $path;

        $chat    = Chat::findOrFail($chatId);
        $message = $chat->messages()->create([
            'sender_id' => $request->user()->id,
            'content'   => '📷 Image',
            'image_url' => $url,
        ]);

        return response()->json(['success' => true, 'image_url' => $url, 'message_id' => $message->id]);
    }
}
