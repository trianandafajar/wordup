<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'course_id', 'completed_lessons', 'total_lessons', 'progress_percent', 'started_at', 'completed_at'])]
class UserCourseProgress extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'completed_lessons' => 'integer',
            'total_lessons' => 'integer',
            'progress_percent' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
