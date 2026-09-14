<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportConversation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            ->paginate(20)
            ->appends(['status' => $status]);

        return view('admin.support.index', compact('conversations', 'status'));
    }

    public function show(string $id): View
    {
        \Log::info('SUPPORT_SHOW_HIT', ['id' => $id]);
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

    public function download(string $id): StreamedResponse
    {
        $conversation = SupportConversation::with('messages', 'user')->findOrFail($id);

        $name = $conversation->user->full_name
            ?? $conversation->guest_email
            ?? 'Guest visitor';

        $lines = [];
        $lines[] = 'Support Conversation Transcript';
        $lines[] = "Contact: {$name}";
        $lines[] = 'Status: ' . ucfirst($conversation->status);
        $lines[] = 'Started: ' . $conversation->created_at->format('M j, Y g:ia');
        $lines[] = str_repeat('-', 50);
        $lines[] = '';

        foreach ($conversation->messages as $message) {
            $sender = match ($message->sender) {
                'agent' => 'Agent',
                'ai' => 'AI',
                default => $name,
            };
            $timestamp = $message->created_at->format('M j, g:ia');
            $lines[] = "[{$timestamp}] {$sender}:";
            $lines[] = $message->body;
            $lines[] = '';
        }

        $content = implode("\n", $lines);
        $filename = 'support-' . substr($conversation->id, 0, 8) . '.txt';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, ['Content-Type' => 'text/plain']);
    }
}
