<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ContractorWebhookController;
use App\Http\Controllers\Api\V1\MaintenanceRequestController;
use App\Http\Controllers\Api\V1\PropertyController;
use App\Http\Controllers\Api\V1\UnitController;
use Illuminate\Support\Facades\Route;


Route::prefix("v1")->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);


    Route::post(
        '/webhooks/contractor-status',
        ContractorWebhookController::class
    );

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // Property Routes
        Route::apiResource('properties', PropertyController::class);

        // Unit Routes
        Route::apiResource('units', UnitController::class);

        Route::post(
            'units/{unit}/assign-tenant',
            [UnitController::class, 'assignTenant']
        )->name('units.assign-tenant');

        // Maintenance Routes
        Route::apiResource('maintenance-requests', MaintenanceRequestController::class);

        Route::post(
            '/maintenance-requests/{maintenanceRequest}/approve',
            [MaintenanceRequestController::class, 'approve']

        )
            ->middleware('can:approve,maintenanceRequest')
            ->name('maintenance-requests.approve');
    });
});
