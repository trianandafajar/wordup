<?php

namespace App\Models;

use App\Enums\LessonProgressStatusEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'lesson_id', 'status', 'best_score', 'attempts_count', 'completed_at'])]
class UserLessonProgress extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => LessonProgressStatusEnum::class,
            'best_score' => 'integer',
            'attempts_count' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
