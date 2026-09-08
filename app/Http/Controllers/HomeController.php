<?php

namespace App\Http\Controllers;

use App\Models\UserCourseProgress;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load course progress (most recent active course)
        $courseProgress = UserCourseProgress::where('user_id', $user->id)
            ->with('course')
            ->orderByDesc('started_at')
            ->first();

        // Recent streak logs
        $recentStreaks = $user->streakLogs()
            ->orderByDesc('activity_date')
            ->limit(7)
            ->get();

        $completedLessons = $courseProgress?->completed_lessons ?? 0;
        $totalLessons = $courseProgress?->total_lessons ?? 1;
        $progressPercent = $courseProgress?->progress_percent ?? 0;

        // Unit progress for display
        $units = [];
        if ($courseProgress) {
            $units = $courseProgress->course->units()->with(['lessons' => function ($q) use ($user) {
                $q->with(['progress' => function ($p) use ($user) {
                    $p->where('user_id', $user->id);
                }]);
            }])->get()->map(function ($unit) {
                $completedInUnit = $unit->lessons->filter(function ($lesson) {
                    return $lesson->progress->first()?->status === 'completed';
                })->count();

                return [
                    'title' => $unit->title,
                    'total' => $unit->lessons->count(),
                    'completed' => $completedInUnit,
                    'status' => $completedInUnit === $unit->lessons->count() ? 'Selesai' : ($completedInUnit > 0 ? 'Sedang berjalan' : 'Terkunci'),
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
