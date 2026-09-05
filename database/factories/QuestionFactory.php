<?php

namespace Database\Factories;

use App\Enums\QuestionDifficultyEnum;
use App\Enums\QuestionTypeEnum;
use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'type' => QuestionTypeEnum::MultipleChoice->value,
            'difficulty_level' => QuestionDifficultyEnum::Beginner->value,
            'question_text' => fake()->sentence(),
            'audio_url' => null,
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
