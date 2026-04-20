<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCashier
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isCashier()) {
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. Cashier privileges required.');
        }

        return $next($request);
    }
}
