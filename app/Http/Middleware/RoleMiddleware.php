<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        if ($role && $request->user()->role !== $role && $request->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
