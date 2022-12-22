<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EveController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocatorController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\SignaturesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::withoutMiddleware(!App::environment('production') ? ['ajax.only'] : [])
    ->group(function () {
        Route::get('/getWormholeClasses', [HomeController::class, 'getWormholeClasses']);
        Route::get('/getRatsDamages', [HomeController::class, 'getRatsDamages']);
        Route::get('/getSolarSystems/{search}', [LocatorController::class, 'list']);
        Route::get('/getSolarSystemInfo/{system}', [LocatorController::class, 'get']);
        Route::get('/getSignatures/{system}', [SignaturesController::class, 'index'])->name('api.get-signatures');
        Route::get('/getLocation', [EveController::class, 'locate']);
        Route::get('/getLocationsHistory', [LocatorController::class, 'getLocationsHistory']);
        Route::post('/deleteSignature', [SignaturesController::class, 'destroy']);
        Route::post('/updateSignatures', [SignaturesController::class, 'update']);
        Route::post('/getRoute', [RoutesController::class, 'buildRoute']);

        // Route::post('/waypoint', [EveController::class, 'waypoint'])->name('api.waypoint');
        // Route::post('/route', [RoutesController::class, 'buildRoute'])->name('api.route');
    });
