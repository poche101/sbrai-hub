<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'status', 'start_date', 'end_date',
        'amount_paid', 'payment_method', 'transaction_id',
        'payment_gateway',
    ];

    protected $casts = [
        'start_date'  => 'datetime',
        'end_date'    => 'datetime',
        'amount_paid' => 'float',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }

    public function getDaysRemainingAttribute(): int
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }
}
