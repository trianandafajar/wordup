<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminApiAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
