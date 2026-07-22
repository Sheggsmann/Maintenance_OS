<?php

use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('ensures assigned contractor can start work', function () {
    $contractor = User::factory()->create();
    $contractor->assignRole(RoleEnum::CONTRACTOR->value);

    $maintenanceRequest = MaintenanceRequest::factory()->create([
        'assigned_to' => $contractor->id,
        'status' => MaintenanceStatusEnum::ASSIGNED,
    ]);

    $response = $this
        ->actingAs($contractor)
        ->postJson("/api/v1/maintenance-requests/{$maintenanceRequest->id}/start");

    $response->assertOk();

    expect($maintenanceRequest->fresh()->status)
        ->toBe(MaintenanceStatusEnum::IN_PROGRESS);

    expect($maintenanceRequest->fresh()->started_at)
        ->not->toBeNull();
});

it('ensures admin cannot start work order', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::ASSIGNED,
    ]);

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/start")
        ->assertForbidden();
});


it('ensures request must exist', function () {
    $contractor = User::factory()->create();
    $contractor->assignRole(RoleEnum::CONTRACTOR->value);

    $this
        ->actingAs($contractor)
        ->postJson("/api/v1/maintenance-requests/{99999}/start")
        ->assertNotFound();
});
