<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // 1. IMPORT SANCTUM TRAIT
use App\Notifications\ResetPasswordNotification;


class User extends Authenticatable
{
    // 2. ADD HasApiTokens IN THE USE LIST BELOW
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuids;

    protected $fillable = [
        'full_name', 'email', 'phone', 'password', 'role',
        'avatar_url', 'kyc_status', 'is_verified',
        'business_name', 'business_address', 'cac_number',
        'google_id', 'facebook_id',
        'rating', 'review_count',
        'email_verified_at', 'phone_verified_at',
        'identity_verified_at', 'identity_type', 'identity_number',
        'kyc_documents', 'settings', 'fcm_token',
        'voucher_balance', 'last_login_at',
        'confirmation_token', 'account_confirmed_at',
        'kyc_rejection_reason', 'last_seen_at',
    ];

    protected $hidden = ['password', 'remember_token', 'fcm_token'];

    protected $casts = [
        'email_verified_at'    => 'datetime',
        'phone_verified_at'    => 'datetime',
        'identity_verified_at' => 'datetime',
        'account_confirmed_at' => 'datetime',
        'last_login_at'        => 'datetime',
        'last_seen_at'         => 'datetime',
        'is_verified'          => 'boolean',
        'kyc_documents'        => 'array',
        'settings'             => 'array',
        'rating'               => 'float',
        'voucher_balance'      => 'float',
    ];

    // ─── Relationships ──────────────────────────────────────
    public function listings()
    {
        return $this->hasMany(Listing::class, 'vendor_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'vendor_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'vendor_id')
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->latest();
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'buyer_id')
            ->orWhere('vendor_id', $this->id);
    }

    // ─── Accessors ──────────────────────────────────────────
    public function getCanPostListingAttribute(): bool
    {
        return $this->kyc_status === 'verified' &&
               $this->subscriptions()
                    ->where('status', 'active')
                    ->where('end_date', '>', now())
                    ->exists();
    }

    /**
     * Calculate the expected count based on the account role.
     */
    public function getExpectedKycCount(): int
    {
        return $this->role === 'vendor' ? 4 : 3;
    }

    /**
     * Check if the specific required fields are present.
     */
    public function hasUploadedAllKycDocuments(): bool
    {
        // 1. Core items required by BOTH roles
        if (empty($this->email) || empty($this->phone) || empty($this->identity_number)) {
            return false;
        }

        // 2. Additional validation requirement for vendors
        if ($this->role === 'vendor' && empty($this->cac_number)) {
            return false;
        }

        return true;
    }

    // ─── Settings helpers ────────────────────────────────────
    // Read from the `settings` JSON column with the same defaults as
    // AuthController::defaultSettings(), so a null/partial settings
    // blob still behaves correctly everywhere these are checked.

    public function wantsNotification(string $key): bool
    {
        // Matches AuthController::defaultSettings() — every notification
        // defaults to on except promotional messages.
        $default = $key === 'promotions' ? false : true;
        return (bool) ($this->settings['notifications'][$key] ?? $default);
    }

    public function privacyAllows(string $key): bool
    {
        return (bool) ($this->settings['privacy'][$key] ?? true);
    }

    public function getIsOnlineAttribute(): bool
    {
        if (!$this->privacyAllows('show_online_status')) {
            return false;
        }

        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }


public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPasswordNotification($token));
}
}
