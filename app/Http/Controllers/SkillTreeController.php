<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class SkillTreeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get the active course (first active course)
        $course = Course::where('is_active', true)->first();

        if (! $course) {
            abort(404, 'Belum ada course yang tersedia');
        }

        // Load units with lessons and user progress
        $units = $course->units()->with(['lessons' => function ($q) use ($user) {
            $q->with(['progress' => function ($p) use ($user) {
                $p->where('user_id', $user->id);
            }])->orderBy('order');
        }])->orderBy('order')->get();

        // Flatten all lessons to determine unlock status
        $allLessons = $units->flatMap->lessons->sortBy('order')->values();

        $previousCompleted = true;
        foreach ($allLessons as $lesson) {
            $progress = $lesson->progress->first();

            if ($progress) {
                $lesson->user_status = $progress->status;
            } else {
                if ($previousCompleted) {
                    $lesson->user_status = 'available';
                } else {
                    $lesson->user_status = 'locked';
                }
            }

            // Next lesson is locked unless current is completed
            $previousCompleted = ($progress?->status === 'completed');
        }

        // Group lessons back by unit
        $unitsWithStatus = $units->map(function ($unit) {
            return [
                'unit' => $unit,
                'lessons' => $unit->lessons->map(function ($lesson) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'order' => $lesson->order,
                        'type' => $lesson->type,
                        'xp_reward' => $lesson->xp_reward,
                        'status' => $lesson->user_status ?? 'locked',
                        'best_score' => $lesson->progress->first()?->best_score ?? 0,
                    ];
                }),
            ];
        });

        return view('livewire.user.skill-tree', [
            'course' => $course,
            'units' => $unitsWithStatus,
        ]);
    }
}
