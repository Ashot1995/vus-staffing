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
        'is_active' => 'boolean',
    ];

    /**
     * Get the quick_links attribute, ensuring it's always an array
     */
    public function getQuickLinksAttribute($value)
    {
        // First, try to get the raw attribute value from database
        $rawValue = $this->attributes['quick_links'] ?? null;
        
        // If we have a raw value, try to decode it
        if ($rawValue !== null) {
            if (is_array($rawValue)) {
                return $rawValue;
            }
            
            if (is_string($rawValue) && !empty($rawValue)) {
                $decoded = json_decode($rawValue, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
            }
        }
        
        // Fallback: handle the processed value (in case it's already been processed)
        if (is_array($value)) {
            return $value;
        }
        
        if (is_string($value) && !empty($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }
        
        // Return empty array as final fallback
        return [];
    }

    /**
     * Set the quick_links attribute, ensuring it's stored as JSON
     */
    public function setQuickLinksAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['quick_links'] = json_encode($value);
        } elseif (is_string($value)) {
            // If it's already JSON, validate it, otherwise store as-is
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->attributes['quick_links'] = $value;
            } else {
                $this->attributes['quick_links'] = json_encode([]);
            }
        } else {
            $this->attributes['quick_links'] = json_encode([]);
        }
    }

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
