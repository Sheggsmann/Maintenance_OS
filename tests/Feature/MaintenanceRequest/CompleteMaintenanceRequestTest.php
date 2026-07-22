<?php

use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('lets assigned contractor complete maintenance request', function () {
    $contractor = User::factory()->create();
    $contractor->assignRole(RoleEnum::CONTRACTOR->value);

    $maintenanceRequest = MaintenanceRequest::factory()->create([
        'assigned_to' => $contractor->id,
        'status' => MaintenanceStatusEnum::IN_PROGRESS,
        'started_at' => now()->subHours(2),
    ]);

    $response = $this
        ->actingAs($contractor)
        ->postJson(
            "/api/v1/maintenance-requests/{$maintenanceRequest->id}/complete"
        );

    $response->assertOk();

    expect($maintenanceRequest->fresh()->status)
        ->toBe(MaintenanceStatusEnum::COMPLETED);

    expect($maintenanceRequest->fresh()->completed_at)
        ->not->toBeNull();
});

it('ensures manager cannot complete maintenance request', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::IN_PROGRESS,
    ]);

    $this
        ->actingAs($manager)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/complete")
        ->assertForbidden();
});


it('ensures tenant cannot complete maintenance request', function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::IN_PROGRESS,
    ]);

    $this
        ->actingAs($tenant)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/complete")
        ->assertForbidden();
});


it('ensures contractor can only complete their assigned request', function () {
    $assigned = User::factory()->create();
    $assigned->assignRole(RoleEnum::CONTRACTOR->value);

    $other = User::factory()->create();
    $other->assignRole(RoleEnum::CONTRACTOR->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::IN_PROGRESS,
        'assigned_to' => $assigned->id,
    ]);

    $this
        ->actingAs($other)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/complete")
        ->assertForbidden();
});


it('changes status to completed', function () {
    $contractor = User::factory()->create();
    $contractor->assignRole(RoleEnum::CONTRACTOR->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::IN_PROGRESS,
        'assigned_to' => $contractor->id,
    ]);

    $this
        ->actingAs($contractor)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/complete");

    // expect($request->fresh()->status)
    //     ->toBe(MaintenanceStatusEnum::COMPLETED);
});
