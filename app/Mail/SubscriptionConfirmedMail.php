<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Subscription $subscription;

    public function __construct(User $user, Subscription $subscription)
    {
        $this->user = $user;
        $this->subscription = $subscription;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Sbrai Subscription is Active! 🎉',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-confirmed',
            with: [
                'name'           => $this->user->full_name,
                'amount'         => $this->subscription->amount_paid,
                'method'         => $this->subscription->payment_method,
                'startDate'      => $this->subscription->start_date->format('d M Y'),
                'endDate'        => $this->subscription->end_date->format('d M Y'),
                'transactionId'  => $this->subscription->transaction_id,
            ],
        );
    }
}
