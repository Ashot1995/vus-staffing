<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'cv_path',
        'cover_letter',
        'is_spontaneous',
        'status',
        'start_date_option',
        'start_date',
    ];

    protected $casts = [
        'is_spontaneous' => 'boolean',
        'start_date' => 'date',
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
