<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (! $request->user()?->can($permission)) {
            abort(403, "Permission tidak mencukupi.");
        }
        return $next($request);
    }
}
