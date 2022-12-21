<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EveController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Routed in Vue (backend routes)
Route::get('/', static fn () => view('index'));
Route::get('/route', static fn () => view('index'));
Route::get('/locate', static fn () => view('index'));
Route::get('/login', static fn () => view('index'));
// Route::view('/privacy', 'privacy')->name('privacy');
// Route::view('/legal', 'legal')->name('legal');
// Route::view('/contact', 'contact')->name('contact');
// Route::post('/contact', [EveController::class, 'contact']);

// Routed in Laravel
Route::get('/auth', [EveController::class, 'auth'])->name('auth');
Route::get('/callback', [EveController::class, 'callback']);
Route::get('/logout', [EveController::class, 'logout'])->name('logout');

// Route::get('/route', [RoutesController::class, 'route'])->name('route')->middleware('auth');
