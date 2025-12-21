<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'first_name',
        'surname',
        'date_of_birth',
        'is_18_or_older',
        'phone',
        'address',
        'cv_path',
        'additional_files',
        'personal_image_path',
        'cover_letter',
        'is_spontaneous',
        'status',
        'start_date_option',
        'start_date',
        'consent_type',
        'driving_license_b',
        'driving_license_own_car',
        'other',
        'additional_information',
    ];

    protected $casts = [
        'is_spontaneous' => 'boolean',
        'start_date' => 'date',
        'date_of_birth' => 'date',
        'driving_license_b' => 'boolean',
        'driving_license_own_car' => 'boolean',
        'is_18_or_older' => 'boolean',
        'additional_files' => 'array',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
