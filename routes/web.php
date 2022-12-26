<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiteController;
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
Route::get('/login', static fn () => view('index'));
Route::get('/privacy', static fn () => view('index'));
Route::get('/legal', static fn () => view('index'));
Route::get('/contacts', static fn () => view('index'));

// Routed in Laravel
Route::get('/auth', [AuthController::class, 'auth'])->name('auth');
Route::get('/update', [AuthController::class, 'update'])->name('update');
Route::get('/callback', [AuthController::class, 'callback']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
