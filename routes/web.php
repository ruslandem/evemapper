<?php

use App\Http\Controllers\EveController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;

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
Route::get('/system', [SystemController::class, 'show']);
Route::get('/system/{id}', [SystemController::class, 'show']);

Route::get('/route', [RoutesController::class, 'route']);
