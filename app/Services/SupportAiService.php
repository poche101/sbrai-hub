<?php

namespace App\Services;

use App\Models\SupportConversation;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupportAiService
{
    protected string $systemPrompt = <<<'PROMPT'
You are the customer support assistant for Sbrai Solutions, a Nigerian
marketplace connecting buyers with vendors (building materials, artisans,
logistics, etc.).

You can help with:
- How the platform works: buyer vs vendor accounts, KYC verification,
  vendor subscriptions, the ₦5,000 vendor welcome voucher, posting/finding
  listings, in-app chat between buyers and vendors.
- Account questions: password resets ("use the 'Forgot password' link on
  the sign-in page"), how to switch from buyer to vendor, how KYC/CAC
  verification works.
- General troubleshooting for common issues.

Rules:
- Be concise, warm, and practical. This is customer support, not a sales pitch.
- If you don't know something specific to the user's account (order status,
  payment issues, disputes, anything requiring looking up their actual data
  you don't have), say so plainly and escalate rather than guessing.
- If the user is frustrated, asks for a human, or has an issue outside your
  knowledge (payments, disputes, account bans, bugs), escalate.

Respond with ONLY a JSON object, no other text, in this exact shape:
{"reply": "your message to the user", "escalate": true or false, "escalate_reason": "short reason or empty string"}
PROMPT;

    public function reply(SupportConversation $conversation, string $userMessage): array
    {
        $history = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => [
                'role' => $m->sender === 'visitor' ? 'user' : 'assistant',
                'content' => $m->body,
            ])
            ->toArray();

        $history[] = ['role' => 'user', 'content' => $userMessage];

        $context = $this->accountContext($conversation);

        $response = Http::withHeaders([
            'x-api-key' => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => 'claude-sonnet-4-6',
            'max_tokens' => 500,
            'system' => $this->systemPrompt . "\n\n" . $context,
            'messages' => $history,
        ]);

        if (!$response->successful()) {
            Log::error('Support AI request failed: ' . $response->body());
            return [
                'reply' => "Sorry, I'm having trouble responding right now. I've flagged this for our team to follow up.",
                'escalate' => true,
                'escalate_reason' => 'AI service error: ' . $response->status(),
            ];
        }

        $text = collect($response->json('content', []))
            ->firstWhere('type', 'text')['text'] ?? null;

        $parsed = $text ? json_decode($text, true) : null;

        if (!is_array($parsed) || !isset($parsed['reply'])) {
            // Model didn't return valid JSON — fail safe by escalating
            return [
                'reply' => "Let me connect you with someone from our team who can help with this.",
                'escalate' => true,
                'escalate_reason' => 'AI returned unparseable response',
            ];
        }

        return [
            'reply' => $parsed['reply'],
            'escalate' => (bool) ($parsed['escalate'] ?? false),
            'escalate_reason' => $parsed['escalate_reason'] ?? '',
        ];
    }

    protected function accountContext(SupportConversation $conversation): string
    {
        if (!$conversation->user_id) {
            return "The visitor is not signed in — you don't have account details for them.";
        }

        $user = User::find($conversation->user_id);
        if (!$user) {
            return "The visitor is not signed in — you don't have account details for them.";
        }

        return "Signed-in user context (use only to inform your answer, never dump raw internal data to the user):\n"
            . "- Role: {$user->role}\n"
            . "- KYC status: {$user->kyc_status}\n"
            . "- Account verified: " . ($user->is_verified ? 'yes' : 'no') . "\n"
            . ($user->role === 'vendor'
                ? "- Has active subscription: " . ($user->activeSubscription ? 'yes' : 'no') . "\n"
                : '');
    }
}
