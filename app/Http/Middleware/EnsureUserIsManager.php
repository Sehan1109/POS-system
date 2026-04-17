<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isManager()) {
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. Manager privileges required.');
        }

        return $next($request);
    }
}
