<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCourseProgress;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $courseProgress = UserCourseProgress::where('user_id', $user->id)
            ->with('course')
            ->orderByDesc('started_at')
            ->first();

        $recentStreaks = $user->streakLogs()
            ->orderByDesc('activity_date')
            ->limit(7)
            ->get();

        $units = [];
        if ($courseProgress) {
            $allUnits = $courseProgress->course->units()->with(['lessons' => function ($q) use ($user) {
                $q->with(['progress' => function ($p) use ($user) {
                    $p->where('user_id', $user->id);
                }])->orderBy('order');
            }])->orderBy('order')->get();

            $previousCompleted = true;

            $units = $allUnits->map(function ($unit) use (&$previousCompleted) {
                $lessons = $unit->lessons->sortBy('order')->values();
                $completedInUnit = 0;
                $hasAvailable = false;

                foreach ($lessons as $lesson) {
                    $rawStatus = $lesson->progress->first()?->status;
                    $statusValue = $rawStatus instanceof \BackedEnum ? $rawStatus->value : $rawStatus;

                    if ($statusValue === 'completed') {
                        $completedInUnit++;
                        $previousCompleted = true;
                    } elseif ($statusValue === 'in_progress') {
                        $hasAvailable = true;
                        $previousCompleted = false;
                    } else {
                        if ($previousCompleted) {
                            $hasAvailable = true;
                        }
                        $previousCompleted = false;
                    }
                }

                $allDone = $lessons->count() > 0 && $completedInUnit === $lessons->count();

                return [
                    'title' => $unit->title,
                    'total' => $lessons->count(),
                    'completed' => $completedInUnit,
                    'status' => $allDone
                        ? 'completed'
                        : (($completedInUnit > 0 || $hasAvailable) ? 'in_progress' : 'locked'),
                ];
            });
        }

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'lives' => $user->lives,
                'xp_total' => $user->xp_total,
                'current_streak' => $user->current_streak,
            ],
            'courseProgress' => [
                'completed_lessons' => $courseProgress?->completed_lessons ?? 0,
                'total_lessons' => $courseProgress?->total_lessons ?? 1,
                'progress_percent' => $courseProgress?->progress_percent ?? 0,
            ],
            'units' => $units,
            'recentStreaks' => $recentStreaks,
        ]);
    }
}
