<?php

namespace Database\Factories;

use App\Enums\LessonProgressStatusEnum;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserLessonProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserLessonProgress>
 */
class UserLessonProgressFactory extends Factory
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
            'status' => LessonProgressStatusEnum::NotStarted->value,
            'best_score' => 0,
            'attempts_count' => 0,
            'completed_at' => null,
        ];
    }
}
