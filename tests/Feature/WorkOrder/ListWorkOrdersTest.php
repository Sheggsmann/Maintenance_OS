<?php

use App\Enums\RoleEnum;
use App\Enums\WorkOrderStatusEnum;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkOrder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('allows managers to list work orders', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create([
        'manager_id' => $manager->id,
    ]);

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
    ]);

    $maintenanceRequest = MaintenanceRequest::factory()->create([
        'property_id' => $property->id,
        'unit_id' => $unit->id,
    ]);

    WorkOrder::factory()->count(3)->create([
        'maintenance_request_id' => $maintenanceRequest->id,
    ]);

    $this
        ->actingAs($manager)
        ->getJson('/api/v1/work-orders')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});


it('prevents tenants from listing work orders', function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $this
        ->actingAs($tenant)
        ->getJson('/api/v1/work-orders')
        ->assertForbidden();
});


it('filters work orders by status', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create([
        'manager_id' => $manager->id,
    ]);

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
    ]);

    $maintenanceRequest = MaintenanceRequest::factory()->create([
        'property_id' => $property->id,
        'unit_id' => $unit->id,
    ]);

    WorkOrder::factory()->create([
        'maintenance_request_id' => $maintenanceRequest->id,
        'status' => WorkOrderStatusEnum::PENDING,
    ]);

    WorkOrder::factory()->create([
        'maintenance_request_id' => $maintenanceRequest->id,
        'status' => WorkOrderStatusEnum::COMPLETED,
    ]);

    $this
        ->actingAs($manager)
        ->getJson('/api/v1/work-orders?status=completed')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'status' => 'completed',
        ]);
});


it('paginates work orders', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create([
        'manager_id' => $manager->id,
    ]);

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
    ]);

    $maintenanceRequest = MaintenanceRequest::factory()->create([
        'property_id' => $property->id,
        'unit_id' => $unit->id,
    ]);

    WorkOrder::factory()->count(20)->create([
        'maintenance_request_id' => $maintenanceRequest->id,
    ]);

    $this
        ->actingAs($manager)
        ->getJson('/api/v1/work-orders?per_page=10')
        ->assertOk()
        ->assertJsonCount(10, 'data');
});
