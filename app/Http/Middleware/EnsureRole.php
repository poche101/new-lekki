<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Usage in routes: ->middleware('role:content')
 * Checks the permission KEY (see config/roles.php), not a specific role name,
 * so "any role that can manage content" passes `role:content`.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        // Goes through the Gate (see AppServiceProvider::boot()), same path as
        // Blade's @can — so the sidebar and the route guard never disagree.
        abort_unless($user && $user->is_active && $user->can($permission), 403, 'You do not have access to this section.');

        return $next($request);
    }
}
