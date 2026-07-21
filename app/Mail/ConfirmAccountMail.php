<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $confirmUrl;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->confirmUrl = url('/api/v1/auth/confirm-account/' . $user->confirmation_token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Please Confirm Your Sbrai Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.confirm-account',
            with: [
                'name'       => $this->user->full_name,
                'confirmUrl' => $this->confirmUrl,
            ],
        );
    }
}
