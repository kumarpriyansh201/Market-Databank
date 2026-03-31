<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        $roleMatches = in_array($user->role, $roles, true);

        if (!$roleMatches && in_array('user', $roles, true)) {
            $roleMatches = in_array($user->role, ['user', 'viewer', 'contributor'], true);
        }

        if (!$roleMatches) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
