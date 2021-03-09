<?php

namespace App\Domain\Role\Middleware;

use App\Domain\Role\Exceptions\PermissionDenied;
use Closure;
use Illuminate\Http\Request;

class Role
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$roles = array_slice(func_get_args(), 2);
		$teamId = (int) $request->route('team')['id'];

		foreach ($roles as $role) {
			if (auth()->user()->hasRole($teamId, $role)) {
				return $next($request);
			}
		}

		throw new PermissionDenied;
	}
}
