<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
   public function handle(Request $request, Closure $next, $role)
    {
        // 1. Check if the user is actually logged in first
        if (!auth()->check()) {
            return redirect('/login');
        }

        // 2. Check if their database role matches the required role for the page
        if (auth()->user()->role !== $role) {
            // If they don't match, kick them out with a 403 Access Denied error
            abort(403, 'Unauthorized Access. You do not have the correct permissions for this page.');
        }

        // 3. If they pass the check, let them through to the page!
        return $next($request);
    }
}
