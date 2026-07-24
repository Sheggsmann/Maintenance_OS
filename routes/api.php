<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ContractorWebhookController;
use App\Http\Controllers\Api\V1\MaintenanceRequestController;
use App\Http\Controllers\Api\V1\PropertyController;
use App\Http\Controllers\Api\V1\UnitController;
use App\Http\Controllers\Api\V1\WorkOrderController;
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

        // Maintenance Routes
        Route::apiResource('maintenance-requests', MaintenanceRequestController::class);

        // Route::post(
        //     'maintenance-requests/{maintenanceRequest}/work-order',
        //     [WorkOrderController::class, 'generate']
        // )
        //     ->name('maintenance-requests.work-order');

        Route::post(
            '/maintenance-requests/{maintenanceRequest}/approve',
            [MaintenanceRequestController::class, 'approve']

        )
            ->middleware('can:approve,maintenanceRequest')
            ->name('maintenance-requests.approve');

        Route::post(
            '/webhooks/contractor-status',
            ContractorWebhookController::class
        );

        // Route::post(
        //     'maintenance-requests/{maintenanceRequest}/assign',
        //     [MaintenanceRequestController::class, 'assign']
        // )
        //     ->name('maintenance-requests.assign');

        // Route::post(
        //     'maintenance-requests/{maintenanceRequest}/start',
        //     [MaintenanceRequestController::class, 'start']
        // )->name('maintenance-requests.start');

        // Route::post(
        //     'maintenance-requests/{maintenanceRequest}/complete',
        //     [MaintenanceRequestController::class, 'complete']
        // )->name('maintenance-requests.complete');

        // Route::post(
        //     'maintenance-requests/{maintenanceRequest}/cancel',
        //     [MaintenanceRequestController::class, 'cancel']
        // )->name('maintenance-requests.cancel');


    });
});
