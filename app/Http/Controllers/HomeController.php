<?php

namespace App\Http\Controllers;

use App\Models\UserCourseProgress;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(): \Illuminate\View\View
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

        $completedLessons = $courseProgress?->completed_lessons ?? 0;
        $totalLessons = $courseProgress?->total_lessons ?? 1;
        $progressPercent = $courseProgress?->progress_percent ?? 0;

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
                        ? 'Selesai'
                        : (($completedInUnit > 0 || $hasAvailable) ? 'Sedang berjalan' : 'Terkunci'),
                ];
            });
        }

        return view('livewire.user.home', [
            'user' => $user,
            'courseProgress' => $courseProgress,
            'completedLessons' => $completedLessons,
            'totalLessons' => $totalLessons,
            'progressPercent' => $progressPercent,
            'units' => $units,
            'recentStreaks' => $recentStreaks,
        ]);
    }
}
