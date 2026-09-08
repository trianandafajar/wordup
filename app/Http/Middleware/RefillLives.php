<?php

namespace App\Http\Middleware;

use App\Services\LifeService;
use Closure;
use Illuminate\Http\Request;

class RefillLives
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user) {
            app(LifeService::class)->refillIfNeeded($user);
        }

        return $next($request);
    }
}
