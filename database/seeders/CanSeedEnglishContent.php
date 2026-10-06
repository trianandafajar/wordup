<?php

namespace Database\Seeders;

use App\Enums\QuestionTypeEnum;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\File;

trait CanSeedEnglishContent
{
    protected function syncAnswers(Question $question, array $q): void
    {
        if ($q['type'] === QuestionTypeEnum::FillInTheBlank->value) {
            QuestionOption::where('question_id', $question->id)->delete();
            QuestionAnswer::query()->updateOrCreate(
                ['question_id' => $question->id],
                ['correct_text' => $q['answer']]
            );

            return;
        }

        QuestionAnswer::where('question_id', $question->id)->delete();
        QuestionOption::where('question_id', $question->id)->delete();

        foreach ($q['options'] as $opt) {
            QuestionOption::query()->create([
                'question_id' => $question->id,
                'option_text' => $opt['text'],
                'is_correct' => $opt['correct'],
            ]);
        }
    }

    protected function smartCopyImage(int $unit, int $lesson, string $fileName, string $destPath, string $fallbackSource = 'images/mascots/2.png'): ?string
    {
        $sourcePath = "images/units/unit-{$unit}/lesson-{$lesson}/{$fileName}";
        $fullSourcePath = public_path($sourcePath);

        if (! File::exists($fullSourcePath)) {
            $fullSourcePath = public_path($fallbackSource);
            if (! File::exists($fullSourcePath)) {
                return null;
            }
        }

        $targetPath = storage_path("app/public/{$destPath}");
        File::ensureDirectoryExists(dirname($targetPath));
        File::copy($fullSourcePath, $targetPath);

        return $destPath;
    }

    protected function mc(string $text, array $options, string $correct, string $difficulty = 'beginner', ?string $imageUrl = null): array
    {
        return [
            'type' => 'multiple_choice',
            'difficulty' => $difficulty,
            'text' => $text,
            'image_url' => $imageUrl,
            'options' => array_map(
                fn ($o) => ['text' => $o, 'correct' => $o === $correct],
                $options
            ),
        ];
    }

    protected function fib(string $text, string $answer, string $difficulty = 'beginner', ?string $imageUrl = null): array
    {
        return [
            'type' => QuestionTypeEnum::FillInTheBlank->value,
            'difficulty' => $difficulty,
            'text' => $text,
            'answer' => $answer,
            'image_url' => $imageUrl,
        ];
    }

    protected function lesson(string $title, string $explanation, array $questions, int $xp = 20, ?string $imageUrl = null): array
    {
        return [
            'title' => $title,
            'type' => 'reading',
            'xp' => $xp,
            'explanation' => $explanation,
            'image_url' => $imageUrl,
            'questions' => $questions,
        ];
    }
}
