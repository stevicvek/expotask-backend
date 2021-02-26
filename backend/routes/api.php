<?php

use Illuminate\Support\Facades\Route;
use App\Domain\Auth\Controllers\SignUpController;

Route::get('/', function () {
	return response()
		->json('Welcome to Expotask API!', 200);
});

Route::prefix('auth')->group(function () {
	Route::post('/register', SignUpController::class)->name('register');
});
