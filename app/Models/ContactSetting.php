<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'address_en',
        'address_sv',
        'hours_weekdays_en',
        'hours_weekdays_sv',
        'hours_weekend_en',
        'hours_weekend_sv',
    ];
}
