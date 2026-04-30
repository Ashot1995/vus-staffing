<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Job extends Model
{
    protected $table = 'job_listings';

    protected $fillable = [
        'title',
        'description',
        'location',
        'employment_type',
        'salary',
        'requirements',
        'responsibilities',
        'is_published',
        'deadline',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'deadline' => 'date',
    ];

    protected static function booted(): void
    {
        $clearSitemap = fn () => Cache::forget(\App\Http\Controllers\SitemapController::CACHE_KEY);
        static::saved($clearSitemap);
        static::deleted($clearSitemap);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get the translated employment type label
     */
    public function getEmploymentTypeLabelAttribute(): string
    {
        $translationKey = 'messages.jobs.filter.' . str_replace('-', '_', $this->employment_type);
        return __($translationKey);
    }

    /**
     * Get all employment type options with translations
     */
    public static function getEmploymentTypeOptions(): array
    {
        return [
            'full-time' => __('messages.jobs.filter.full_time'),
            'part-time' => __('messages.jobs.filter.part_time'),
            'contract' => __('messages.jobs.filter.contract'),
            'temporary' => __('messages.jobs.filter.temporary'),
        ];
    }
}
