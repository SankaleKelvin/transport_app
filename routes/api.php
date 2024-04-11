<?php

use App\Http\Controllers\AreasController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TruckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/area", [AreasController::class, 'createArea']);
Route::get("/area", [AreasController::class, 'readAllAreas']);
Route::get("/area/{id}", [AreasController::class, 'readArea']);
Route::post("/area/{id}", [AreasController::class, 'updateArea']);
Route::delete("/area/{id}", [AreasController::class, 'deleteArea']);

Route::post("/driver", [DriverController::class, 'createDriver']);
Route::get("/driver", [DriverController::class, 'readAllDrivers']);
Route::get("/driver/{id}", [DriverController::class, 'readDriver']);
Route::post("/driver/{id}", [DriverController::class, 'updateDriver']);
Route::delete("/driver/{id}", [DriverController::class, 'deleteDriver']);

Route::post("/truck", [TruckController::class, 'createTruck']);
Route::get("/truck", [TruckController::class, 'readAllTrucks']);
Route::get("/truck/{id}", [TruckController::class, 'readTruck']);
Route::post("/truck/{id}", [TruckController::class, 'updateTruck']);
Route::delete("/truck/{id}", [TruckController::class, 'deleteTruck']);
