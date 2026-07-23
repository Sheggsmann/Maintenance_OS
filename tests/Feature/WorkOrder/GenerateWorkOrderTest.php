<?php

use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\WorkOrderStatusEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Models\WorkOrder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('lets admin can generate a work order', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    Sanctum::actingAs($admin);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN->value,
    ]);

    $response = $this->postJson(
        "/api/v1/maintenance-requests/{$request->id}/work-order",
        [
            'scope_of_work' => 'Replace leaking sink',
            'estimated_cost' => 35000,
            'estimated_hours' => 3,
        ]
    );

    $response
        ->assertCreated();

    expect(WorkOrder::count('*'))
        ->toBe(1);

    expect(WorkOrder::first()->status)
        ->toBe(
            WorkOrderStatusEnum::PENDING->value
        );
});

it('lets manager can generate a work order', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    Sanctum::actingAs($manager);

    $maintenanceRequest = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN->value,
    ]);

    $response = $this->postJson(
        "/api/v1/maintenance-requests/{$maintenanceRequest->id}/work-order",
        [
            'scope_of_work' => 'Replace leaking pipe under kitchen sink.',
            'estimated_cost' => 45000,
            'estimated_hours' => 3,
        ]
    );

    $response->assertCreated();

    expect(WorkOrder::count('*'))->toBe(1);
    expect(WorkOrder::first()->status)
        ->toBe(
            WorkOrderStatusEnum::PENDING->value
        );
});


it('ensures tenant cannot generate a work order', function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(
        RoleEnum::TENANT->value
    );

    Sanctum::actingAs($tenant);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN->value,
    ]);

    $this->postJson(
        "/api/v1/maintenance-requests/{$request->id}/work-order",
        [
            'scope_of_work' => 'Fix leak',
        ]
    )
        ->assertForbidden();
});

it('ensures contractor cannot generate a work order', function () {
    $contractor = User::factory()->create();
    $contractor->assignRole(
        RoleEnum::CONTRACTOR->value
    );

    Sanctum::actingAs($contractor);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN->value,
    ]);

    $this->postJson(
        "/api/v1/maintenance-requests/{$request->id}/work-order",
        [
            'scope_of_work' => 'Fix leak',
        ]
    )
        ->assertForbidden();
});


it('cannot generate more than one work order', function () {
    $manager = User::factory()->create();
    $manager->assignRole(
        RoleEnum::MANAGER->value
    );

    Sanctum::actingAs($manager);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN->value,
    ]);

    WorkOrder::factory()->create([
        'maintenance_request_id' => $request->id,
    ]);

    $this->postJson(
        "/api/v1/maintenance-requests/{$request->id}/work-order",
        [
            'scope_of_work' => 'Fix leak',
        ]
    )
        ->assertConflict();
});

it('requires scope of work', function () {
    $manager = User::factory()->create();
    $manager->assignRole(
        RoleEnum::MANAGER->value
    );

    Sanctum::actingAs($manager);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN->value,
    ]);

    $this->postJson(
        "/api/v1/maintenance-requests/{$request->id}/work-order",
        []
    )
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'scope_of_work',
        ]);
});
