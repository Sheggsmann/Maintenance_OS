<?php

use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('lets admin assign a contractor', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $contractor = User::factory()->create();
    $contractor->assignRole(RoleEnum::CONTRACTOR->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN,
        'assigned_to' => null,
    ]);

    $response = $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/assign", [
            'contractor_id' => $contractor->id,
        ]);

    $response
        ->assertOk();

    expect($request->fresh()->assigned_to)
        ->toBe($contractor->id);

    expect($request->fresh()->status)
        ->toBe(MaintenanceStatusEnum::ASSIGNED);
});

it('cannot let tenant assign contractor', function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $contractor = User::factory()->create();
    $contractor->assignRole(RoleEnum::CONTRACTOR->value);

    $request = MaintenanceRequest::factory()->create();

    $this
        ->actingAs($tenant)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/assign", [
            'contractor_id' => $contractor->id,
        ])
        ->assertForbidden();
});

it('cannot let contractor assign contractor', function () {
    $user = User::factory()->create();
    $user->assignRole(RoleEnum::CONTRACTOR->value);

    $contractor = User::factory()->create();
    $contractor->assignRole(RoleEnum::CONTRACTOR->value);

    $request = MaintenanceRequest::factory()->create();

    $this
        ->actingAs($user)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/assign", [
            'contractor_id' => $contractor->id,
        ])
        ->assertForbidden();
});

it('ensures assigned user must have contractor role', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN,
    ]);

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/assign", [
            'contractor_id' => $manager->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('contractor_id');
});

it('cannot assign contractor twice', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $contractorOne = User::factory()->create();
    $contractorOne->assignRole(RoleEnum::CONTRACTOR->value);

    $contractorTwo = User::factory()->create();
    $contractorTwo->assignRole(RoleEnum::CONTRACTOR->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN,
        'assigned_to' => $contractorOne->id,
    ]);

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/assign", [
            'contractor_id' => $contractorTwo->id,
        ])
        ->assertUnprocessable();

    expect($request->fresh()->assigned_to)
        ->toBe($contractorOne->id);
});

it('ensures guest cannot assign contractor', function () {
    $contractor = User::factory()->create();
    $contractor->assignRole(RoleEnum::CONTRACTOR->value);

    $request = MaintenanceRequest::factory()->create();

    $this
        ->postJson("/api/v1/maintenance-requests/{$request->id}/assign", [
            'contractor_id' => $contractor->id,
        ])
        ->assertUnauthorized();
});
