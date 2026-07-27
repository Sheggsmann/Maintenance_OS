<?php

use App\Enums\RoleEnum;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('allows manager to assign tenant to unit', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $property = Property::factory()->create([
        'manager_id' => $manager->id,
    ]);

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
    ]);

    $this
        ->actingAs($manager)
        ->postJson(
            "/api/v1/units/{$unit->id}/assign-tenant",
            [
                'tenant_id' => $tenant->id,
            ]
        )
        ->assertOk();

    expect($unit->fresh()->tenant_id)
        ->toBe($tenant->id);
});

it('prevents non-managers from assigning tenants', function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $property = Property::factory()->create();

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
    ]);

    $this
        ->actingAs($tenant)->postJson(
            "/api/v1/units/{$unit->id}/assign-tenant",
            [
                'tenant_id' => $tenant->id,
            ]
        )
        ->assertForbidden();
});
