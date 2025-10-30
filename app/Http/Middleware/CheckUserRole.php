<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
	public function handle(Request $request, Closure $next, string $role): Response
    {
		// Cho phép truyền nhiều vai trò: role:admin,editor,manager
		$allowedRoles = array_filter(array_map('trim', explode(',', $role)));
		$currentRole = Auth::check() ? (string) Auth::user()->role : '';

		if (!Auth::check() || (count($allowedRoles) > 0 && !in_array($currentRole, $allowedRoles, true))) {
			abort(403, 'Unauthorized action.');
		}

        return $next($request);
    }
}
