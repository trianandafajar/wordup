<?php

namespace Database\Seeders;

use App\Enums\LessonProgressStatusEnum;
use App\Enums\QuestionDifficultyEnum;
use App\Enums\QuestionTypeEnum;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionOption;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserCourseProgress;
use App\Models\UserLessonProgress;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
        ]);

        $user = User::query()->firstOrCreate(['email' => 'user@example.com'], [
            'name' => 'Budi Saputra',
            'password' => 'password',
            'xp_total' => 340,
            'current_streak' => 4,
            'longest_streak' => 9,
            'lives' => 5,
            'last_activity_date' => CarbonImmutable::today(),
        ]);

        $user->onboarding()->firstOrCreate([], [
            'target_language' => 'English',
            'learning_goal' => 'casual',
            'proficiency_level' => 'beginner',
            'completed_at' => now(),
        ]);

        $course = Course::query()->firstOrCreate(['title' => 'English foundations'], [
            'description' => 'Build a practical English habit, one useful phrase at a time.',
            'language_target' => 'English',
            'level' => 'beginner',
            'is_active' => true,
        ]);

        $unitData = [
            [
                'title' => 'Greetings & Introductions',
                'lessons' => [
                    ['title' => 'Meet and greet', 'type' => 'reading'],
                    ['title' => 'Talk about yourself', 'type' => 'listening'],
                    ['title' => 'Ask simple questions', 'type' => 'speaking'],
                    ['title' => 'Daily Phrases', 'type' => 'quiz'],
                ],
            ],
            [
                'title' => 'Daily conversations',
                'lessons' => [
                    ['title' => 'At the coffee shop', 'type' => 'reading'],
                    ['title' => 'Make a plan', 'type' => 'listening'],
                    ['title' => 'Keep the conversation going', 'type' => 'speaking'],
                ],
            ],
            [
                'title' => 'Build your confidence',
                'lessons' => [
                    ['title' => 'Tell a short story', 'type' => 'reading'],
                    ['title' => 'Share your opinion', 'type' => 'quiz'],
                ],
            ],
        ];

        $lessons = collect();
        foreach ($unitData as $unitOrder => $unitItem) {
            $unit = Unit::query()->firstOrCreate([
                'course_id' => $course->id,
                'order' => $unitOrder + 1,
            ], ['title' => $unitItem['title']]);

            foreach ($unitItem['lessons'] as $lessonOrder => $lessonInfo) {
                $lesson = Lesson::query()->firstOrCreate([
                    'unit_id' => $unit->id,
                    'order' => $lessonOrder + 1,
                ], [
                    'title' => $lessonInfo['title'],
                    'type' => $lessonInfo['type'],
                    'xp_reward' => 20,
                ]);
                $lessons->push($lesson);
            }
        }

        // Add questions for each lesson
        $sampleQuestions = [
            [
                'type' => QuestionTypeEnum::MultipleChoice->value,
                'text' => 'How do you say "Halo" in English?',
                'options' => [
                    ['text' => 'Hello', 'correct' => true],
                    ['text' => 'Good night', 'correct' => false],
                    ['text' => 'Goodbye', 'correct' => false],
                    ['text' => 'Thank you', 'correct' => false],
                ],
            ],
            [
                'type' => QuestionTypeEnum::MultipleChoice->value,
                'text' => 'What is the correct response to "How are you?"',
                'options' => [
                    ['text' => 'I am fine, thank you', 'correct' => true],
                    ['text' => 'My name is John', 'correct' => false],
                    ['text' => 'Yes, please', 'correct' => false],
                    ['text' => 'Good morning', 'correct' => false],
                ],
            ],
            [
                'type' => QuestionTypeEnum::FillInTheBlank->value,
                'text' => 'Lengkapi terjemahan: "Selamat pagi" = Good ____',
                'answer' => 'morning',
                'options' => [],
            ],
            [
                'type' => QuestionTypeEnum::MultipleChoice->value,
                'text' => 'How do you ask someone\'s name?',
                'options' => [
                    ['text' => 'What is your name?', 'correct' => true],
                    ['text' => 'Where do you live?', 'correct' => false],
                    ['text' => 'How old are you?', 'correct' => false],
                    ['text' => 'Nice to meet you', 'correct' => false],
                ],
            ],
        ];

        foreach ($lessons as $lesson) {
            foreach ($sampleQuestions as $qOrder => $qData) {
                $question = Question::query()->firstOrCreate([
                    'lesson_id' => $lesson->id,
                    'order' => $qOrder + 1,
                ], [
                    'type' => $qData['type'],
                    'difficulty_level' => QuestionDifficultyEnum::Beginner->value,
                    'question_text' => $qData['text'],
                ]);

                if ($qData['type'] === QuestionTypeEnum::FillInTheBlank->value && isset($qData['answer'])) {
                    QuestionAnswer::query()->firstOrCreate([
                        'question_id' => $question->id,
                    ], [
                        'correct_text' => $qData['answer'],
                    ]);
                } else {
                    foreach ($qData['options'] as $opt) {
                        QuestionOption::query()->firstOrCreate([
                            'question_id' => $question->id,
                            'option_text' => $opt['text'],
                        ], ['is_correct' => $opt['correct']]);
                    }
                }
            }
        }

        UserCourseProgress::query()->updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'completed_lessons' => 2,
                'total_lessons' => $lessons->count(),
                'progress_percent' => round((2 / max(1, $lessons->count())) * 100, 2),
                'started_at' => CarbonImmutable::today()->subDays(8),
            ],
        );

        foreach ($lessons as $index => $lesson) {
            UserLessonProgress::query()->updateOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                [
                    'status' => $index < 2
                        ? LessonProgressStatusEnum::Completed->value
                        : ($index === 2 ? LessonProgressStatusEnum::InProgress->value : LessonProgressStatusEnum::NotStarted->value),
                    'best_score' => $index < 2 ? 90 : 0,
                    'attempts_count' => $index < 2 ? 1 : 0,
                    'completed_at' => $index < 2 ? CarbonImmutable::today()->subDays(3 - $index) : null,
                ],
            );
        }

        foreach ([0, 1, 3, 5] as $daysAgo) {
            $user->streakLogs()->updateOrCreate(
                ['activity_date' => CarbonImmutable::today()->subDays($daysAgo)->toDateString()],
                ['xp_earned_that_day' => 20 + ($daysAgo % 2) * 10],
            );
        }
    }
}
