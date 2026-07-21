<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'document_number',
        'front_image_path', 'back_image_path', 'selfie_path',
        'status', 'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
