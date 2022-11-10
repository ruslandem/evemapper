<?php

use App\Http\Controllers\EveController;
use App\Http\Controllers\WormholesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

// main page
Route::get('/', [EveController::class, 'main']);

// authentication
Route::get('/auth', [EveController::class, 'auth']);
Route::get('/callback', [EveController::class, 'callback']);
Route::get('/logout', [EveController::class, 'logout']);

// solar system search
Route::get('/locate', [EveController::class, 'locate']);
Route::get('/system', [WormholesController::class, 'show']);
Route::get('/system/{id}', [WormholesController::class, 'show']);
