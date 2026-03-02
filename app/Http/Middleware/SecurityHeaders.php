<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only add headers if enabled via env to avoid affecting live site unintentionally
        if (!config('app.secure_headers', env('SECURE_HEADERS', false))) {
            return $response;
        }

        $hstsMax = (int) env('HSTS_MAX_AGE', 31536000);
        $includeSub = env('HSTS_INCLUDE_SUBDOMAINS', true) ? '; includeSubDomains' : '';
        $preload = env('HSTS_PRELOAD', false) ? '; preload' : '';

        $response->headers->set('Strict-Transport-Security', "max-age={$hstsMax}{$includeSub}{$preload}");
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', env('REFERRER_POLICY', 'no-referrer-when-downgrade'));
        $response->headers->set('Permissions-Policy', env('PERMISSIONS_POLICY', 'interest-cohort=()'));

        // Minimal CSP; adjust via env if you need to allow specific sources
        $csp = env('CSP_POLICY', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
