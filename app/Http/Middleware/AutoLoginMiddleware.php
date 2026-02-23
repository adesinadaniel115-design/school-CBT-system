<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class AutoLoginMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Auto-login disabled - use manual login instead
        // This allows proper logout functionality
        
        return $next($request);
    }
}
