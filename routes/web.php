<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routed in Vue (backend routes)
Route::get('/', static fn () => view('index'));
Route::get('/route', static fn () => view('index'));
Route::get('/locate', static fn () => view('index'));
Route::get('/appraisal', static fn () => view('index'));
Route::get('/login', static fn () => view('index'));
Route::get('/license', static fn () => view('index'));
Route::get('/admin', static fn () => view('index'));

// Routed in Laravel
Route::get('/auth', [AuthController::class, 'auth'])->name('auth-get');
Route::get('/callback', [AuthController::class, 'callback'])->name('auth-callback');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth-logout');
