<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportConversation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $conversations = SupportConversation::with(['user', 'messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->orderByRaw("status = 'escalated' desc")
            ->latest('updated_at')
            ->paginate(20);

        return view('admin.support.index', compact('conversations', 'status'));
    }

    public function show(string $id): View
    {
        $conversation = SupportConversation::with('messages', 'user')->findOrFail($id);

        return view('admin.support.show', compact('conversation'));
    }

    public function reply(Request $request, string $id): RedirectResponse
    {
        $request->validate(['message' => 'required|string|max:4000']);

        $conversation = SupportConversation::findOrFail($id);

        $conversation->messages()->create([
            'sender' => 'agent',
            'body' => $request->message,
        ]);

        $conversation->touch();

        return back()->with('status', 'Reply sent.');
    }

    public function resolve(string $id): RedirectResponse
    {
        $conversation = SupportConversation::findOrFail($id);
        $conversation->update(['status' => 'resolved']);

        return redirect()->route('admin.support.index')->with('status', 'Conversation marked resolved.');
    }

    public function messages(string $id): JsonResponse
    {
        $conversation = SupportConversation::with('messages')->findOrFail($id);

        return response()->json([
            'status' => $conversation->status,
            'messages' => $conversation->messages->map(fn ($m) => [
                'sender' => $m->sender,
                'body' => $m->body,
                'at' => $m->created_at->format('M j, g:ia'),
            ]),
        ]);
    }
}
