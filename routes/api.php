<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocatorController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\SignaturesController;
use App\Http\Controllers\SiteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group([], function () {
    // Main website and static data requests
    Route::post('/sendContactForm', [SiteController::class, 'contact']);
    Route::get('/getWormholeClasses', [SiteController::class, 'getWormholeClasses']);
    Route::get('/getRatsDamages', [SiteController::class, 'getRatsDamages']);
    // Location and history
    Route::get('/getSolarSystems/{search}', [LocatorController::class, 'list']);
    Route::get('/getSolarSystemInfo/{system}', [LocatorController::class, 'get']);
    Route::get('/getLocation', [LocatorController::class, 'locate']);
    Route::get('/getLocationsHistory', [LocatorController::class, 'getLocationsHistory']);
    // Signatures
    Route::get('/getSignatures/{system}', [SignaturesController::class, 'index'])->name('api.get-signatures');
    Route::post('/deleteSignature', [SignaturesController::class, 'destroy']);
    Route::post('/updateSignatures', [SignaturesController::class, 'update']);
    // Solar systems route
    Route::post('/getRoute', [RoutesController::class, 'buildRoute']);
    Route::post('/addAutopilotWaypoint', [RoutesController::class, 'waypoint']);
});
