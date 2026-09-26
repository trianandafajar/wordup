<?php

namespace App\Http\Controllers\Api;

use App\Enums\LearningGoalEnum;
use App\Enums\ProficiencyLevelEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index()
    {
        return response()->json([
            'learningGoals' => LearningGoalEnum::cases(),
            'proficiencyLevels' => ProficiencyLevelEnum::cases(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'target_language' => ['required', 'string', 'max:255'],
            'learning_goal' => ['required', 'string', 'in:'.implode(',', LearningGoalEnum::getAllValues())],
            'proficiency_level' => ['required', 'string', 'in:'.implode(',', ProficiencyLevelEnum::getAllValues())],
        ]);

        $user = Auth::user();

        $user->onboarding()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'target_language' => $request->target_language,
                'learning_goal' => $request->learning_goal,
                'proficiency_level' => $request->proficiency_level,
                'completed_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Onboarding completed successfully.',
        ]);
    }
}
