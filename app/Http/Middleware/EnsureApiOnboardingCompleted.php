<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureApiOnboardingCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && (! $user->onboarding || ! $user->onboarding->completed_at)) {
            return response()->json([
                'message' => 'Onboarding not completed.',
            ], 403);
        }

        return $next($request);
    }
}