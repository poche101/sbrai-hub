<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id', 'type', 'amount', 'currency',
        'description', 'reference', 'status', 'balance',
    ];

    protected $casts = ['amount' => 'float', 'balance' => 'float'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transaction) {
            if (empty($transaction->id)) {
                $transaction->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
