<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    protected $fillable = [
        'brand_text',
        'location_en',
        'location_sv',
        'email',
        'quick_links_title_en',
        'quick_links_title_sv',
        'quick_links',
        'copyright_en',
        'copyright_sv',
        'is_active',
    ];

    protected $casts = [
        'quick_links' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
