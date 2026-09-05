<?php

namespace Database\Factories;

use App\Enums\AttemptStatusEnum;
use App\Models\Lesson;
use App\Models\LessonAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LessonAttempt>
 */
class LessonAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lesson_id' => Lesson::factory(),
            'attempt_number' => 1,
            'score' => 0,
            'status' => AttemptStatusEnum::InProgress->value,
            'started_at' => now(),
            'completed_at' => null,
        ];
    }
}
