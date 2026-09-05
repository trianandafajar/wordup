<?php

namespace Database\Seeders;

use App\Enums\LessonProgressStatusEnum;
use App\Enums\QuestionDifficultyEnum;
use App\Enums\QuestionTypeEnum;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
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
            'name' => 'User',
            'password' => 'password',
            'xp_total' => 340,
            'current_streak' => 4,
            'longest_streak' => 9,
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
            ['title' => 'Everyday essentials', 'lessons' => ['Meet and greet', 'Talk about yourself', 'Ask simple questions']],
            ['title' => 'Daily conversations', 'lessons' => ['At the coffee shop', 'Make a plan', 'Keep the conversation going']],
            ['title' => 'Build your confidence', 'lessons' => ['Tell a short story', 'Share your opinion']],
        ];

        $lessons = collect();
        foreach ($unitData as $unitOrder => $unitItem) {
            $unit = Unit::query()->firstOrCreate([
                'course_id' => $course->id,
                'order' => $unitOrder + 1,
            ], ['title' => $unitItem['title']]);

            foreach ($unitItem['lessons'] as $lessonOrder => $title) {
                $lesson = Lesson::query()->firstOrCreate([
                    'unit_id' => $unit->id,
                    'order' => $lessonOrder + 1,
                ], ['title' => $title, 'xp_reward' => 20]);
                $lessons->push($lesson);
            }
        }

        $firstLesson = $lessons->first();
        if ($firstLesson) {
            $question = Question::query()->firstOrCreate([
                'lesson_id' => $firstLesson->id,
                'order' => 1,
            ], [
                'type' => QuestionTypeEnum::MultipleChoice->value,
                'difficulty_level' => QuestionDifficultyEnum::Beginner->value,
                'question_text' => 'How do you say “Halo” in English?',
            ]);

            QuestionOption::query()->firstOrCreate([
                'question_id' => $question->id,
                'option_text' => 'Hello',
            ], ['is_correct' => true]);
            QuestionOption::query()->firstOrCreate([
                'question_id' => $question->id,
                'option_text' => 'Good night',
            ], ['is_correct' => false]);
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
