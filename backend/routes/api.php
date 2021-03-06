<?php

use App\Domain\Auth\Controllers\GetUserController;
use Illuminate\Support\Facades\Route;
use App\Domain\Auth\Controllers\SignInController;
use App\Domain\Auth\Controllers\SignUpController;
use App\Domain\Team\Controllers\CreateTeamController;
use App\Domain\Auth\Controllers\RefreshTokenController;
use App\Domain\Team\Controllers\AcceptMembershipController;
use App\Domain\Team\Controllers\GetTeamBySlugController;

Route::prefix('auth')->group(function () {
	Route::post('/register', SignUpController::class)->name('register');
	Route::post('/login', SignInController::class)->name('login');
	Route::get('/refresh', RefreshTokenController::class)->name('refresh');
});

Route::prefix('auth')
	->as('auth.')
	->middleware('auth:api')
	->group(function () {
		Route::get('/me', GetUserController::class)->name('me');
	});

Route::prefix('team')
	->as('team.')
	->middleware('auth:api')
	->group(function () {
		Route::post('/', CreateTeamController::class)->name('create');
		Route::get('/{team:slug}', GetTeamBySlugController::class)->name('getBySlug');
	});

Route::prefix('membership')
	->as('membership.')
	->middleware('auth:api')
	->group(function () {
		Route::get('/accept', AcceptMembershipController::class)->name('accept');
	});
