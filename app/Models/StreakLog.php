<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'activity_date', 'xp_earned_that_day'])]
class StreakLog extends Model
{
    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'xp_earned_that_day' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
