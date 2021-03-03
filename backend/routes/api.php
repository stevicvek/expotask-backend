<?php

use Illuminate\Support\Facades\Route;
use App\Domain\Auth\Controllers\SignInController;
use App\Domain\Auth\Controllers\SignUpController;
use App\Domain\Team\Controllers\CreateTeamController;
use App\Domain\Auth\Controllers\RefreshTokenController;
use App\Domain\Team\Controllers\AcceptMembershipController;


Route::prefix('auth')->group(function () {
	Route::post('/register', SignUpController::class)->name('register');
	Route::post('/login', SignInController::class)->name('login');
	Route::get('/refresh', RefreshTokenController::class)->name('refresh');
});

Route::prefix('team')
	->as('team.')
	->middleware('auth:api')
	->group(function () {
		Route::post('/', CreateTeamController::class)->name('create');
		Route::get('/accept', AcceptMembershipController::class)->name('accept');
	});
