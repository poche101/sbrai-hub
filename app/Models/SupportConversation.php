<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SupportConversation extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'guest_email', 'guest_token', 'status'];

    public function messages()
    {
        return $this->hasMany(SupportMessage::class, 'conversation_id')->orderBy('created_at');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
