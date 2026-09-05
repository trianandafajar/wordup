<?php

namespace Database\Factories;

use App\Models\LessonAttempt;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserAnswer>
 */
class UserAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attempt_id' => LessonAttempt::factory(),
            'question_id' => Question::factory(),
            'answer_given' => fake()->sentence(2),
            'is_correct' => false,
            'answered_at' => now(),
        ];
    }
}
