<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'listing_type', 'icon', 'image_url',
        'sort_order', 'is_active', 'created_by',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Deterministic color-gradient for a category/listing name, used as the
     * card thumbnail whenever a listing has no photo yet. The exact same
     * hash + palette is reimplemented in resources/js/site/gradient.js for
     * client-rendered cards (browse, favourites) so the same name always
     * produces the same gradient everywhere on the site.
     */
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
     * Override standard binding to support both numeric database IDs
     * in the admin panel and text slugs in the frontend applications.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (is_numeric($value)) {
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
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name);
            }
        });
    }

    /**
 * Force Laravel to bind and lookup categories using their text slug column
 * instead of corrupt, duplicate primary key integer IDs.
 */
public function getRouteKeyName()
{
    return 'slug';
}
}
