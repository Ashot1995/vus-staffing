<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
