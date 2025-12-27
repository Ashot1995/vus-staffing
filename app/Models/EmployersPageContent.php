<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployersPageContent extends Model
{
    protected $table = 'employers_page_content';

    protected $fillable = [
        'content_en',
        'content_sv',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
