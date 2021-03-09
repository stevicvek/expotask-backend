<?php

namespace App\Domain\Role\Middleware;

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
		$teamId = (int) $request->route('team');

		foreach ($roles as $role) {
			if (auth()->user()->hasRole($teamId, $role)) {
				return $next($request);
			}
		}

		return abort(401, 'Permission denied!');
	}
}
