<?php

use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use App\Enums\RoleEnum;
use App\Enums\UnitStatusEnum;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('lets Admin create a maintenance request', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $property = Property::factory()->create();

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
        'status' => UnitStatusEnum::OCCUPIED
    ]);

    $response = $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests", [
            'unit_id' => $unit->id,
            'title' => 'Kitchen Sink Leak',
            'description' => 'Water leaking under sink',
            'priority' => MaintenancePriorityEnum::HIGH->value,
            'category' => MaintenanceCategoryEnum::PLUMBING->value,
        ]);

    $response
        ->assertCreated();

    $this->assertDatabaseHas('maintenance_requests', [
        'unit_id' => $unit->id,
        'title' => 'Kitchen Sink Leak',
    ]);
});

it("lets Tenants create maintenance requests", function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $property = Property::factory()->create();

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
        'status' => UnitStatusEnum::OCCUPIED,
        'tenant_id' => $tenant->id
    ]);

    $response = $this
        ->actingAs($tenant)
        ->postJson("/api/v1/maintenance-requests", [
            'unit_id' => $unit->id,
            'title' => 'Kitchen Sink Leak',
            'description' => 'Water leaking under sink',
            'priority' => MaintenancePriorityEnum::HIGH->value,
            'category' => MaintenanceCategoryEnum::PLUMBING->value,
        ]);

    $response
        ->assertCreated();

    $this->assertDatabaseHas('maintenance_requests', [
        'unit_id' => $unit->id,
        'title' => 'Kitchen Sink Leak',
    ]);
});


it("lets Managers create maintenance requests", function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(['manager_id' => $manager->id]);

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
        'status' => UnitStatusEnum::OCCUPIED
    ]);

    $response = $this
        ->actingAs($manager)
        ->postJson("/api/v1/maintenance-requests", [
            'unit_id' => $unit->id,
            'title' => 'Kitchen Sink Leak',
            'description' => 'Water leaking under sink',
            'priority' => MaintenancePriorityEnum::HIGH->value,
            'category' => MaintenanceCategoryEnum::PLUMBING->value,
        ]);

    $response
        ->assertCreated();

    $this->assertDatabaseHas('maintenance_requests', [
        'unit_id' => $unit->id,
        'title' => 'Kitchen Sink Leak',
    ]);
});


it("cannot let Contractors create maintenance request", function () {
    $contractor = User::factory()->create();
    $contractor->assignRole(RoleEnum::CONTRACTOR->value);

    $property = Property::factory()->create();

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
        'status' => UnitStatusEnum::OCCUPIED,
    ]);

    $response = $this
        ->actingAs($contractor)
        ->postJson("/api/v1/maintenance-requests", [
            'unit_id' => $unit->id,
            'title' => 'Kitchen Sink Leak',
            'description' => 'Water leaking under sink',
            'priority' => MaintenancePriorityEnum::HIGH->value,
            'category' => MaintenanceCategoryEnum::PLUMBING->value,
        ]);

    $response
        ->assertForbidden();
});


it("cannot let tenant create request for another unit", function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $tenant2 = User::factory()->create();
    $tenant2->assignRole(RoleEnum::TENANT->value);

    $property = Property::factory()->create();

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
        'status' => UnitStatusEnum::OCCUPIED,
        'tenant_id' => $tenant->id,
    ]);

    $response = $this
        ->actingAs($tenant2)
        ->postJson("/api/v1/maintenance-requests", [
            'unit_id' => $unit->id,
            'title' => 'Kitchen Sink Leak',
            'description' => 'Water leaking under sink',
            'priority' => MaintenancePriorityEnum::HIGH->value,
            'category' => MaintenanceCategoryEnum::PLUMBING->value,
        ]);

    $response
        ->assertUnprocessable();
});

it("cannot create maintenance request for vacant unit", function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $property = Property::factory()->create();
    $unit = Unit::factory()->create([
        'property_id' => $property->id,
        'status' => UnitStatusEnum::VACANT->value,
    ]);

    $response =  $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests", [
            'unit_id' => $unit->id,
            'title' => 'Kitchen Sink Leak',
            'description' => 'Water leaking under sink',
            'priority' => MaintenancePriorityEnum::HIGH->value,
            'category' => MaintenanceCategoryEnum::PLUMBING->value,
        ]);

    $response->assertUnprocessable();
});

// it("should not have a contractor assigned initially", function () {});
