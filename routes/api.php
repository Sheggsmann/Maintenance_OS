<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PropertyController;
use App\Http\Controllers\Api\V1\UnitController;
use Illuminate\Support\Facades\Route;


Route::prefix("v1")->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // Property Routes
        Route::apiResource('properties', PropertyController::class);

        // Unit Routes
        Route::apiResource('units', UnitController::class);
    });
});
