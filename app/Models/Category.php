<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // Indicate that IDs are non-incrementing UUID strings
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'slug', 'listing_type', 'icon', 'image_url',
        'sort_order', 'is_active', 'created_by',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function gradientFor(string $name): string
    {
        $palette = [
            ['#e8734a', '#c94f3a'], // coral
            ['#14b8a6', '#0d9488'], // teal
            ['#3b82f6', '#2563eb'], // blue
            ['#8b5cf6', '#7c3aed'], // purple
            ['#84a05a', '#6b8342'], // olive
            ['#38bdf8', '#0ea5e9'], // sky
        ];

        $hash = 0;
        foreach (str_split($name) as $ch) {
            $hash = ($hash * 31 + ord($ch)) & 0xFFFFFFFF;
        }

        [$from, $to] = $palette[$hash % count($palette)];

        return "linear-gradient(135deg, {$from}, {$to})";
    }

    /**
     * Resolve model binding by UUID (admin) or slug (frontend).
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Check if value is a valid UUID pattern
        if (Str::isUuid($value)) {
            return $this->where('id', $value)->firstOrFail();
        }

        return $this->where('slug', $value)->firstOrFail();
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'category_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOfType($q, string $type)
    {
        return $q->where('listing_type', $type);
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            // Auto-generate UUID if missing
            if (empty($category->id)) {
                $category->id = (string) Str::uuid();
            }

            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
