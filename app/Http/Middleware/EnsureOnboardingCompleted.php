<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOnboardingCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && (! $user->onboarding || ! $user->onboarding->completed_at)) {
            return redirect()->route('user.onboarding');
        }

        return $next($request);
    }
}
