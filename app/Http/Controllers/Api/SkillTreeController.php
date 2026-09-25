<?php

namespace App\Http\Controllers\Api;

use App\Enums\LessonProgressStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\UserCourseProgress;
use App\Models\UserLessonProgress;
use Illuminate\Support\Facades\Auth;

class SkillTreeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $course = Course::where('is_active', true)->first();

        if (! $course) {
            return response()->json(['message' => 'No active course found'], 404);
        }

        UserCourseProgress::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'completed_lessons' => 0,
                'total_lessons' => $course->units->flatMap->lessons->count(),
                'progress_percent' => 0,
                'started_at' => now(),
            ]
        );

        foreach ($course->units->flatMap->lessons as $lesson) {
            UserLessonProgress::firstOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                ['status' => LessonProgressStatusEnum::NotStarted]
            );
        }

        $units = $course->units()->with(['lessons' => function ($q) use ($user) {
            $q->with(['progress' => function ($p) use ($user) {
                $p->where('user_id', $user->id);
            }])->orderBy('order');
        }])->orderBy('order')->get();

        $allLessons = $units->flatMap(fn ($unit) => $unit->lessons->values())->values();

        $previousCompleted = true;
        foreach ($allLessons as $lesson) {
            $rawStatus = $lesson->progress->first()?->status;

            if ($rawStatus === LessonProgressStatusEnum::Completed) {
                $lesson->user_status = 'completed';
                $previousCompleted = true;
            } elseif ($rawStatus === LessonProgressStatusEnum::InProgress) {
                $lesson->user_status = 'available';
                $previousCompleted = false;
            } else {
                $lesson->user_status = $previousCompleted ? 'available' : 'locked';
                $previousCompleted = false;
            }
        }

        if ($user->lives <= 0) {
            foreach ($allLessons as $lesson) {
                if ($lesson->user_status !== 'completed') {
                    $lesson->user_status = 'locked';
                }
            }
        }

        $unitsWithStatus = $units->map(function ($unit) {
            return [
                'unit' => $unit->title,
                'lessons' => $unit->lessons->map(function ($lesson) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'status' => $lesson->user_status ?? 'locked',
                        'best_score' => $lesson->progress->first()?->best_score ?? 0,
                    ];
                }),
            ];
        });

        return response()->json([
            'course' => $course->title,
            'units' => $unitsWithStatus,
            'lives' => $user->lives,
        ]);
    }
}
