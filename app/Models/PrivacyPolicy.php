<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivacyPolicy extends Model
{
    protected $fillable = [
        'content_en',
        'content_sv',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active privacy policy
     */
    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
