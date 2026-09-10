<?php

namespace App\Http\Controllers\Auth;

use App\Enums\LearningGoalEnum;
use App\Enums\ProficiencyLevelEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function create(): View
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect('/admin');
        }

        if ($user->onboarding && $user->onboarding->completed_at) {
            return redirect()->route('user.home');
        }

        return view('auth.onboarding', [
            'learningGoals' => LearningGoalEnum::cases(),
            'proficiencyLevels' => ProficiencyLevelEnum::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
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

        return redirect()->route('user.home');
    }
}
