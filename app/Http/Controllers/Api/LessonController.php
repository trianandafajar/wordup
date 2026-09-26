<?php

namespace App\Http\Controllers\Api;

use App\Enums\AttemptStatusEnum;
use App\Enums\LessonProgressStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\UserAnswer;
use App\Models\UserCourseProgress;
use App\Models\UserLessonProgress;
use App\Services\LeagueService;
use App\Services\LifeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * @tags Lesson
     * @summary Get lesson details
     * @return \App\Http\Resources\LessonResource
     */
    public function show($lessonId)
    {
        $user = Auth::user();
        $lesson = Lesson::with('questions.options', 'questions.answer', 'unit')->findOrFail($lessonId);

        return new \App\Http\Resources\LessonResource($lesson->load('questions.options'));
    }

    /**
     * @tags Lesson
     * @summary Submit lesson answers
     */
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
            if (! $question) continue;

            if ($question->type === 'fill_in_the_blank' || ($question->options->count() <= 0 && $question->answer)) {
                $correct = strtolower(trim((string) $question->answer?->correct_text));
                $given = strtolower(trim((string) $answerGiven));
                $isCorrect = $given !== '' && $given === $correct;
            } else {
                $isCorrect = $question->options->contains(fn($option) => $option->id === (int) $answerGiven && $option->is_correct);
            }

            if ($isCorrect) $correctCount++;

            $answeredQuestions->push([
                'question' => $question,
                'answer_given' => (string) $answerGiven,
                'is_correct' => $isCorrect,
            ]);
        }

        $finalScore = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $passed = $finalScore >= 80;
        $attemptNumber = ($user->lessonAttempts()->where('lesson_id', $lesson->id)->max('attempt_number') ?? 0) + 1;

        $attempt = $user->lessonAttempts()->create([
            'lesson_id' => $lesson->id,
            'attempt_number' => $attemptNumber,
            'score' => $finalScore,
            'status' => $passed ? AttemptStatusEnum::Completed : AttemptStatusEnum::InProgress,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        foreach ($answeredQuestions as $answer) {
            UserAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $answer['question']->id],
                ['answer_given' => $answer['answer_given'], 'is_correct' => $answer['is_correct'], 'answered_at' => now()]
            );
        }

        $progress = UserLessonProgress::where('user_id', $user->id)->where('lesson_id', $lesson->id)->first();
        UserLessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            [
                'status' => $passed ? LessonProgressStatusEnum::Completed : LessonProgressStatusEnum::InProgress,
                'best_score' => max($progress?->best_score ?? 0, $finalScore),
                'attempts_count' => ($progress?->attempts_count ?? 0) + 1,
                'completed_at' => $passed ? now() : null,
            ]
        );

        $xpEarned = 0;
        if ($passed) {
            $xpEarned = $lesson->xp_reward + (($user->current_streak >= 2) ? 10 : 0);
            $today = now()->toDateString();
            $user->xp_total += $xpEarned;
            $user->league_week_xp += $xpEarned;
            
            // Streak logic simplified for API
            if ($user->last_activity_date?->toDateString() !== $today) {
                $user->current_streak = ($user->last_activity_date?->toDateString() === now()->subDay()->toDateString()) ? $user->current_streak + 1 : 1;
            }
            $user->longest_streak = max($user->longest_streak, $user->current_streak);
            $user->last_activity_date = $today;
            $user->save();
        } else {
            app(LifeService::class)->loseLife($user);
        }

        return response()->json([
            'score' => $finalScore,
            'xp_earned' => $xpEarned,
            'passed' => $passed,
            'attempt_id' => $attempt->id
        ]);
    }

    /**
     * @tags Lesson
     * @summary Get result of last attempt
     */
    public function result($lessonId)
    {
        $user = Auth::user();
        $attempt = $user->lessonAttempts()
            ->where('lesson_id', $lessonId)
            ->latest()
            ->with('answers.question')
            ->firstOrFail();

        return response()->json([
            'score' => $attempt->score,
            'status' => $attempt->status,
            'answers' => $attempt->answers->map(fn($a) => [
                'question' => $a->question->question_text,
                'given' => $a->answer_given,
                'correct' => (bool) $a->is_correct
            ])
        ]);
    }
}
