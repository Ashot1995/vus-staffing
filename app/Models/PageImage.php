<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageImage extends Model
{
    protected $fillable = [
        'page',
        'section',
        'image_path',
        'alt_text',
        'description',
        'sort_order',
    ];

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get image by page and section
     */
    public static function getImage(string $page, string $section): ?self
    {
        return static::where('page', $page)
            ->where('section', $section)
            ->first();
    }
}
