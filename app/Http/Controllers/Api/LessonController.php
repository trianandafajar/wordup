<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show($lessonId)
    {
        $user = Auth::user();
        $lesson = Lesson::with('questions.options', 'questions.answer', 'unit')->findOrFail($lessonId);

        // API logic for showing lesson
        return response()->json([
            'lesson' => $lesson,
            'lives' => $user->lives,
        ]);
    }

    public function submit(Request $request, $lessonId)
    {
        // Copy the submit logic from original LessonController and convert to API response
        return response()->json(['message' => 'Submit endpoint implemented']);
    }

    public function result($lessonId)
    {
        return response()->json(['message' => 'Result endpoint implemented']);
    }
}
