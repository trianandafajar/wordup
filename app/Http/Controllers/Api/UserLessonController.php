<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $lessons = $user->lessonProgress()
            ->where('status', 'completed')
            ->with(['lesson' => function ($query) {
                $query->with('unit');
            }])
            ->get()
            ->map(function ($progress) {
                return [
                    'lesson_title' => $progress->lesson->title,
                    'unit_title' => $progress->lesson->unit->title,
                    'best_score' => $progress->best_score,
                    'attempts_count' => $progress->attempts_count,
                    'completed_at' => $progress->completed_at ? $progress->completed_at->format('d M Y') : '-',
                ];
            });

        return response()->json(['lessons' => $lessons]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
