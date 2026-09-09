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
                    ['title' => 'Meet and greet', 'type' => 'reading', 'explanation' => '<h3>Saying Hello 👋</h3><p>In English, there are several ways to greet someone:</p><ul><li><strong>Hello</strong> — the most common greeting, works in any situation</li><li><strong>Hi</strong> — informal and friendly, used with friends</li><li><strong>Good morning</strong> — used before noon</li><li><strong>Good afternoon</strong> — used after noon until evening</li><li><strong>Good evening</strong> — used after sunset</li></ul><p>Use <strong>"Hello"</strong> when meeting someone for the first time!</p>'],
                    ['title' => 'Talk about yourself', 'type' => 'listening', 'explanation' => '<h3>Introducing Yourself 🎯</h3><p>When introducing yourself, share these key details:</p><ul><li><strong>Name:</strong> "My name is..." or "I\'m..."</li><li><strong>Where you\'re from:</strong> "I\'m from [country/city]"</li><li><strong>What you do:</strong> "I\'m a [job/student]"</li></ul><p>Practice saying: "Hi, I\'m Ana. I\'m from Indonesia. I\'m a student."</p>'],
                    ['title' => 'Ask simple questions', 'type' => 'speaking', 'explanation' => '<h3>Asking Questions ❓</h3><p>Use these common question patterns:</p><ul><li><strong>What is your name?</strong> — Asking for name</li><li><strong>Where are you from?</strong> — Asking about origin</li><li><strong>How are you?</strong> — Asking how someone feels</li><li><strong>What do you do?</strong> — Asking about job/studies</li></ul><p>Remember: questions usually start with <strong>Wh-</strong> words (What, Where, How, Who) or <strong>Do/Does</strong>!</p>'],
                    ['title' => 'Daily Phrases', 'type' => 'quiz', 'explanation' => '<h3>Useful Daily Phrases 💬</h3><p>Memorize these essential phrases for everyday situations:</p><ul><li><strong>Thank you / Thanks</strong> — Expressing gratitude</li><li><strong>You\'re welcome</strong> — Responding to thanks</li><li><strong>Excuse me</strong> — Getting attention / apologizing</li><li><strong>Sorry</strong> — Apologizing</li><li><strong>Nice to meet you</strong> — When meeting someone new</li><li><strong>See you later / Goodbye</strong> — Saying goodbye</li></ul><p>These phrases will help you sound natural in daily conversations!</p>'],
                ],
            ],
            [
                'title' => 'Daily conversations',
                'lessons' => [
                    ['title' => 'At the coffee shop', 'type' => 'reading', 'explanation' => '<h3>Ordering at a Coffee Shop ☕</h3><p>Key vocabulary and phrases:</p><ul><li><strong>I\'d like a...</strong> — Polite way to order ("I\'d like a latte")</li><li><strong>Can I get a...</strong> — Casual way to order</li><li><strong>To go / For here</strong> — Takeaway or dine-in</li><li><strong>What do you recommend?</strong> — Asking for suggestions</li><li><strong>Sizes:</strong> Small, Medium, Large</li></ul><p>Practice: "I\'d like a medium cappuccino to go, please."</p>'],
                    ['title' => 'Make a plan', 'type' => 'listening', 'explanation' => '<h3>Making Plans 📅</h3><p>Common phrases for making arrangements:</p><ul><li><strong>Are you free on...?</strong> — Checking availability</li><li><strong>What time works for you?</strong> — Asking about time</li><li><strong>Let\'s meet at...</strong> — Suggesting a place/time</li><li><strong>See you then!</strong> — Confirming the plan</li><li><strong>Days:</strong> Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday</li></ul><p>Example: "Are you free on Saturday? Let\'s meet at 2 PM."</p>'],
                    ['title' => 'Keep the conversation going', 'type' => 'speaking', 'explanation' => '<h3>Continuing a Conversation 🗣️</h3><p>Use these techniques to keep talking:</p><ul><li><strong>Follow-up questions:</strong> "Really? Tell me more!" / "Why?" / "How was it?"</li><li><strong>Showing interest:</strong> "That\'s interesting!" / "Wow!" / "No way!"</li><li><strong>Sharing your experience:</strong> "Me too!" / "I had a similar experience..."</li><li><strong>Asking for opinions:</strong> "What do you think?" / "Do you agree?"</li></ul><p>Good listeners make great conversationalists!</p>'],
                ],
            ],
            [
                'title' => 'Build your confidence',
                'lessons' => [
                    ['title' => 'Tell a short story', 'type' => 'reading', 'explanation' => '<h3>Telling a Short Story 📖</h3><p>Structure your story with these elements:</p><ul><li><strong>Beginning:</strong> "One day..." / "Last week..." / "When I was young..."</li><li><strong>Middle:</strong> "Then..." / "After that..." / "Suddenly..."</li><li><strong>End:</strong> "Finally..." / "In the end..." / "It was a great day."</li><li><strong>Time words:</strong> First, Then, Next, After that, Finally</li></ul><p>Practice: Tell a story about your last weekend using these connectors!</p>'],
                    ['title' => 'Share your opinion', 'type' => 'quiz', 'explanation' => '<h3>Sharing Opinions 💭</h3><p>Express your thoughts clearly:</p><ul><li><strong>I think...</strong> / <strong>In my opinion...</strong> — Giving opinion</li><li><strong>I agree because...</strong> / <strong>I disagree because...</strong> — Agreeing/Disagreeing</li><li><strong>For me, ...</strong> / <strong>Personally, ...</strong> — Personal view</li><li><strong>It depends...</strong> — Neutral position</li></ul><p>Remember: It\'s okay to have different opinions. Use "I think" to make it clear it\'s your view!</p>'],
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
                $lesson = Lesson::query()->updateOrCreate([
                    'unit_id' => $unit->id,
                    'order' => $lessonOrder + 1,
                ], [
                    'title' => $lessonInfo['title'],
                    'type' => $lessonInfo['type'],
                    'xp_reward' => 20,
                    'explanation' => $lessonInfo['explanation'] ?? null,
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
