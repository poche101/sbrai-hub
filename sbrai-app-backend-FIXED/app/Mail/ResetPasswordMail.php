<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $resetUrl;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        // The Flutter app has no web frontend, so this link opens a small
        // Blade-rendered reset page served by this same Laravel backend
        // (same domain as the API and admin panel: sbraisolutions.com).
        $this->resetUrl = url('/api/v1/auth/reset-password-form?token=' . $token . '&email=' . urlencode($user->email));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Sbrai Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'name'     => $this->user->full_name,
                'resetUrl' => $this->resetUrl,
                'role'     => $this->user->role,
            ],
        );
    }
}
