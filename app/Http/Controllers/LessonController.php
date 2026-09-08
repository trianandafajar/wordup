<?php

namespace App\Http\Controllers;

use App\Enums\AttemptStatusEnum;
use App\Enums\LessonProgressStatusEnum;
use App\Models\Lesson;
use App\Models\UserAnswer;
use App\Models\UserCourseProgress;
use App\Models\UserLessonProgress;
use App\Services\LeagueService;
use App\Services\LifeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function show($lessonId)
    {
        $user = Auth::user();

        // Redirect to home if user has no lives left
        if ($user->lives <= 0) {
            return redirect()->route('user.home')
                ->with('error', 'Nyawa habis! Tunggu sampai nyawa terisi kembali.');
        }

        $lesson = Lesson::with('questions.options', 'questions.answer', 'unit')->findOrFail($lessonId);

        // Ensure user has course progress created
        $courseProgress = UserCourseProgress::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $lesson->unit->course_id],
            [
                'completed_lessons' => 0,
                'total_lessons' => $lesson->unit->course->units->flatMap->lessons->count(),
                'progress_percent' => 0,
                'started_at' => now(),
            ]
        );

        // Get user progress for this lesson
        $progress = UserLessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        $status = $progress?->status ?? LessonProgressStatusEnum::NotStarted;

        // If already completed, go back to skill tree
        if ($status === LessonProgressStatusEnum::Completed) {
            return redirect()->route('user.learn')
                ->with('message', 'Lesson ini sudah diselesaikan');
        }

        // Determine if this lesson is available: previous lesson in the unit must be completed
        $previousLesson = Lesson::where('unit_id', $lesson->unit_id)
            ->where('order', $lesson->order - 1)
            ->first();

        if ($previousLesson) {
            $prevProgress = UserLessonProgress::where('user_id', $user->id)
                ->where('lesson_id', $previousLesson->id)
                ->first();

            // First lesson of the course: always available
            $isFirstLesson = Lesson::where('unit_id', $lesson->unit_id)
                ->where('order', 1)
                ->first()->id === $lesson->id;

            if (! $isFirstLesson && ($prevProgress === null || $prevProgress->status !== LessonProgressStatusEnum::Completed)) {
                return redirect()->route('user.learn')
                    ->with('error', 'Selesaikan lesson sebelumnya terlebih dahulu');
            }
        }

        $questions = $lesson->questions;

        return view('livewire.user.lesson-practice', [
            'lesson' => $lesson,
            'questions' => $questions,
            'status' => $status,
            'lives' => $user->lives,
        ]);
    }

    public function submit(Request $request, $lessonId)
    {
        $user = Auth::user();
        $lesson = Lesson::with('questions.options', 'questions.answer', 'unit.lessons')->findOrFail($lessonId);

        $request->validate([
            'answers' => 'required|array',
        ]);

        $totalQuestions = $lesson->questions->count();
        $correctCount = 0;
        $answeredQuestions = collect();

        foreach ($request->answers as $questionId => $answerGiven) {
            $question = $lesson->questions->firstWhere('id', (int) $questionId);
            if (! $question) {
                continue;
            }

            if ($question->type === 'fill_in_the_blank' || ($question->options->count() <= 0 && $question->answer)) {
                // Compare text answer (case-insensitive, trimmed)
                $correct = strtolower(trim((string) $question->answer?->correct_text));
                $given = strtolower(trim((string) $answerGiven));
                $isCorrect = $given !== '' && $given === $correct;
            } else {
                // Multiple choice / listening: match option id
                $isCorrect = $question->options->contains(fn($option) => $option->id === (int) $answerGiven && $option->is_correct);
            }

            if ($isCorrect) {
                $correctCount++;
            }

            $answeredQuestions->push([
                'question' => $question,
                'answer_given' => (string) $answerGiven,
                'is_correct' => $isCorrect,
            ]);
        }

        $finalScore = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        // Only award XP if score >= 80
        $passed = $finalScore >= 80;

        // Create a new attempt each time (increment attempt_number)
        $previousAttemptNumber = $user->lessonAttempts()
            ->where('lesson_id', $lesson->id)
            ->max('attempt_number');

        $attemptNumber = ($previousAttemptNumber ?? 0) + 1;

        $attempt = $user->lessonAttempts()->create([
            'lesson_id' => $lesson->id,
            'attempt_number' => $attemptNumber,
            'status' => AttemptStatusEnum::InProgress,
            'started_at' => now(),
        ]);

        $attempt->update([
            'score' => $finalScore,
            'status' => $passed ? AttemptStatusEnum::Completed : AttemptStatusEnum::InProgress,
            'completed_at' => now(),
        ]);

        foreach ($answeredQuestions as $answer) {
            UserAnswer::updateOrCreate(
                [
                    'attempt_id' => $attempt->id,
                    'question_id' => $answer['question']->id,
                ],
                [
                    'answer_given' => $answer['answer_given'],
                    'is_correct' => $answer['is_correct'],
                    'answered_at' => now(),
                ]
            );
        }

        // Load existing progress (for best_score accumulation)
        $progress = UserLessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        // Update lesson progress
        UserLessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            [
                'status' => $passed ? LessonProgressStatusEnum::Completed : LessonProgressStatusEnum::InProgress,
                'best_score' => max($progress?->best_score ?? 0, $finalScore),
                'attempts_count' => ($progress?->attempts_count ?? 0) + 1,
                'completed_at' => $passed ? now() : null,
            ]
        );

        // Award XP only when passing
        $xpEarned = 0;
        if ($passed) {
            $xpEarned = $lesson->xp_reward;

            // Streak logic (fix Carbon vs String comparison bug)
            $today = now()->toDateString();
            $lastActivity = $user->last_activity_date?->toDateString();

            $user->xp_total += $xpEarned;
            $user->league_week_xp += $xpEarned;

            if ($lastActivity !== $today) {
                // Check if last activity was yesterday
                $yesterday = now()->subDay()->toDateString();
                if ($lastActivity === $yesterday) {
                    $user->current_streak += 1;
                } elseif (! $lastActivity) {
                    $user->current_streak = 1;
                } else {
                    // Gap > 1 day, reset streak to 1
                    $user->current_streak = 1;
                }
            }
            $user->longest_streak = max($user->longest_streak, $user->current_streak);
            $user->last_activity_date = $today;

            // Ensure user has league assigned
            if (! $user->league) {
                $user->league = (new LeagueService)->getLeagueForXp($user->xp_total)['key'];
            }

            $user->streakLogs()->updateOrCreate(
                ['activity_date' => $today],
                ['xp_earned_that_day' => DB::raw('xp_earned_that_day + ' . $xpEarned)]
            );

            $user->save();

            // Update course progress
            $courseCompleted = UserLessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $lesson->unit->lessons->pluck('id'))
                ->where('status', LessonProgressStatusEnum::Completed)
                ->count();

            $courseProgress = UserCourseProgress::where('user_id', $user->id)
                ->where('course_id', $lesson->unit->course_id)
                ->first();

            if ($courseProgress) {
                $courseProgress->completed_lessons = $courseCompleted;
                $courseProgress->progress_percent = $courseProgress->total_lessons > 0
                    ? round(($courseCompleted / $courseProgress->total_lessons) * 100, 2)
                    : 0;
                $courseProgress->save();
            }
        } else {
            // Deduct life on failure
            app(LifeService::class)->loseLife($user);
        }

        // Store results in session
        session(['lesson_result' => [
            'score' => $finalScore,
            'xp_earned' => $xpEarned,
            'passed' => $passed,
        ]]);

        return redirect()->route('user.lesson.result', $lesson->id);
    }

    public function result($lessonId)
    {
        $result = session('lesson_result');
        if (! $result) {
            return redirect()->route('user.learn');
        }

        return view('livewire.user.lesson-result', [
            'result' => $result,
        ]);
    }
}
