#!/usr/bin/env bash
echo "09-middleware"
if [ -f app/Http/Middleware/EnsureRole.php ]; then
    echo "  skip: EnsureRole.php"
else
    cat > app/Http/Middleware/EnsureRole.php <<'PHP'
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
        abort(403, 'Anda tidak memiliki role yang diizinkan.');
    }
}
PHP
    echo "  OK: EnsureRole.php"
fi
if [ -f app/Http/Middleware/EnsurePermission.php ]; then
    echo "  skip: EnsurePermission.php"
else
    cat > app/Http/Middleware/EnsurePermission.php <<'PHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! $request->user()?->can($permission)) {
            abort(403, 'Permission tidak mencukupi.');
        }
        return $next($request);
    }
}
PHP
    echo "  OK: EnsurePermission.php"
fi
echo "09-middleware: DONE"
