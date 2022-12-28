<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocatorController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\SignaturesController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

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

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/getStatistics', [AdminController::class, 'getStatistics']);
});

// Moved here auth route, because it depends on redirect with session data, and
// sessions are saved with redirects only in the same routing list.
Route::get('/update', [AuthController::class, 'update'])->name('auth-update');
