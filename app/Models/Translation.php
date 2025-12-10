<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'key',
        'locale',
        'value',
        'group',
    ];

    /**
     * Get translation by key, locale, and group
     */
    public static function getTranslation($key, $locale, $group = 'messages')
    {
        return static::where('key', $key)
            ->where('locale', $locale)
            ->where('group', $group)
            ->value('value');
    }

    /**
     * Get all translations for a locale and group
     */
    public static function getTranslations($locale, $group = 'messages')
    {
        return static::where('locale', $locale)
            ->where('group', $group)
            ->pluck('value', 'key')
            ->toArray();
    }
}
