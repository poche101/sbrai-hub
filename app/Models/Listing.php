<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Listing extends Model
{
    use HasFactory, SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'vendor_id', 'title', 'description', 'price', 'price_unit',
        'category', 'category_id', 'type', 'status', 'location', 'state',
        'image_urls', 'view_count', 'attributes',
    ];

    protected $casts = [
        'image_urls' => 'array',
        'attributes' => 'array',
        'price'      => 'float',
        'view_count' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function categoryRef()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeByCategory($q, $cat)
    {
        return $q->where('category', $cat);
    }

    public function scopeByState($q, $state)
    {
        return $q->where('state', $state);
    }
}
