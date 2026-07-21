<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// ─── Notification Controller ──────────────────────────────────────────
class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $notifications->map(fn($n) => [
                'id'         => $n->id,
                'title'      => $n->data['title'] ?? '',
                'body'       => $n->data['body'] ?? '',
                'type'       => $n->data['type'] ?? 'general',
                'is_read'    => $n->read_at !== null,
                'created_at' => $n->created_at->toISOString(),
                'data'       => $n->data,
            ]),
        ]);
    }

    public function markRead(Request $request, string $id): JsonResponse
    {
        $request->user()->notifications()->findOrFail($id)->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'count'   => $request->user()->unreadNotifications()->count(),
        ]);
    }
}
