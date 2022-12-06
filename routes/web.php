<?php

use App\Http\Controllers\EveController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\LocatorController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SignaturesController;
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

// website
Route::get('/', [EveController::class, 'main']);
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/legal', 'legal')->name('legal');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [EveController::class, 'contact']);

// auth
Route::get('/auth', [EveController::class, 'auth'])->name('auth');
Route::get('/callback', [EveController::class, 'callback']);
Route::get('/logout', [EveController::class, 'logout'])->name('logout');

// solar system search
Route::get('/locator', [LocatorController::class, 'show'])->name('locate')->middleware('auth');
Route::get('/locator/{id}', [LocatorController::class, 'show'])->middleware('auth');
Route::get('/route', [RoutesController::class, 'route'])->name('route')->middleware('auth');

// ajax
Route::prefix('api')->group(function () {
    Route::get('/locate', [EveController::class, 'locate'])->name('api.locate')->middleware('auth');
    Route::post('/waypoint', [EveController::class, 'waypoint'])->name('api.waypoint')->middleware('auth');
    Route::post('/route', [RoutesController::class, 'buildRoute'])->name('api.route');
    Route::post('/systems', [LocatorController::class, 'list'])->name('api.systems');

    Route::get('/signatures', [SignaturesController::class, 'index'])->name('api.getSignatures');
    Route::post('/signatures', [SignaturesController::class, 'update'])->name('api.updateSignatures');
    Route::delete('/signatures', [SignaturesController::class, 'destroy'])->name('api.deleteSignatures');
});
