<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use App\Models\UserCourseProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserCourseProgress>
 */
class UserCourseProgressFactory extends Factory
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
            'course_id' => Course::factory(),
            'completed_lessons' => 0,
            'total_lessons' => fake()->numberBetween(1, 10),
            'progress_percent' => 0,
            'started_at' => now(),
            'completed_at' => null,
        ];
    }
}
