<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CoordinateurMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (!Auth::check() || !Auth::user()->isCoordinateur()) {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }

    return $next($request);
}
}
