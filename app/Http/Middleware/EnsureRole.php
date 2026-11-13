<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) { abort(401); }
        foreach ($roles as $roleCode) {
            if ($user->hasRole($roleCode)) {
                return $next($request);
            }
        }
        abort(403, "Anda tidak memiliki role yang diizinkan.");
    }
}
