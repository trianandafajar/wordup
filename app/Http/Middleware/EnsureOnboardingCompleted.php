<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnsureOnboardingCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->hasRole('admin')) {
            return Redirect::to('/admin');
        }

        if ($user && (! $user->onboarding || ! $user->onboarding->completed_at)) {
            return redirect()->route('user.onboarding');
        }

        return $next($request);
    }
}
