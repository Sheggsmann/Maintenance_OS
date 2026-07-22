<?php

use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('ensures admin can cancel maintenance request', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN,
    ]);

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/cancel", [
            'reason' => 'No contractor available'
        ])
        ->assertOk()
        ->assertJsonPath('data.status', MaintenanceStatusEnum::CANCELLED->value);

    expect($request->fresh()->status)
        ->toBe(MaintenanceStatusEnum::CANCELLED);
});


it('ensures manager can cancel maintenance request', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(['manager_id' => $manager->id]);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN,
        'property_id' => $property->id
    ]);

    $this
        ->actingAs($manager)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/cancel", [
            'reason' => 'No contractor available'
        ])
        ->assertOk();
});

it('ensures tenant can cancel own maintenance request', function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $request = MaintenanceRequest::factory()->create([
        'tenant_id' => $tenant->id,
        'status' => MaintenanceStatusEnum::OPEN,
    ]);

    $this
        ->actingAs($tenant)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/cancel", [
            'reason' => 'No contractor available'
        ])
        ->assertOk();
});


it('cannot cancel completed request', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::COMPLETED,
    ]);
    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/cancel", [
            'reason' => 'No contractor available'
        ])
        ->assertUnprocessable();
});
