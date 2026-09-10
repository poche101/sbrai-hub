<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasUuids;

    protected $fillable = ['conversation_id', 'sender', 'body'];

    public function conversation()
    {
        return $this->belongsTo(SupportConversation::class, 'conversation_id');
    }
}
