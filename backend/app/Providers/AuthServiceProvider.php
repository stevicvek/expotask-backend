<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		Passport::routes(function ($router) {
			$router->forAccessTokens();
			$router->forPersonalAccessTokens();
			$router->forTransientTokens();
		});

		Passport::tokensExpireIn(now()->addMinutes(config('services.passport.access_token_ttl')));
		Passport::refreshTokensExpireIn(now()->addDays(config('services.passport.refresh_token_ttl')));
	}
}
