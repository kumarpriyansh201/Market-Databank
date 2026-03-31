<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsContributorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->isContributor()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
