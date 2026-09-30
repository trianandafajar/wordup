<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    protected $fillable = [
        'user_id',
        'unit_id',
        'level_milestone',
        'is_opened',
    ];
}
