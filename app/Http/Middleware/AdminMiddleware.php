<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.offline_mode') && !config('app.offline_admin_enabled')) {
            abort(404, 'Admin panel is disabled in offline mode.');
        }

        $user = $request->user();

        if (!$user || !$user->is_admin) {
            abort(403, 'Admins only.');
        }

        return $next($request);
    }
}
