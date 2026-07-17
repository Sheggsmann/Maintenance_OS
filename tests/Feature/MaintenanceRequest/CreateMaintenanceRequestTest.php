<?php

use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use App\Enums\RoleEnum;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('admin can create a maintenance request', function () {

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $property = Property::factory()->create();

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
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

it("lets tenants create maintenance requests", function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $property = Property::factory()->create();

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
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

it("cannot let tenant create request for another unit", function () {});

it("cannot create maintenance request for vacant unit", function () {});
