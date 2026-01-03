<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'member_key',
        'name',
        'title',
        'role',
        'phone',
        'email',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get team member by key
     */
    public static function getByKey(string $key): ?self
    {
        return self::where('member_key', $key)
            ->where('is_active', true)
            ->first();
    }
}
