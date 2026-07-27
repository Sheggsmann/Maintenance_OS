<?php

use App\Enums\RoleEnum;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('lets admin view any unit', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $unit = Unit::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->getJson("/api/v1/units/{$unit->id}");

    $response
        ->assertOk()
        ->assertJsonFragment([
            'id' => $unit->id,
            'unit_number' => $unit->unit_number,
        ]);
});

it('lets manager view units in their property', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(['manager_id' => $manager->id]);

    $unit = Unit::factory()->create([
        'property_id' => $property->id
    ]);

    $response = $this
        ->actingAs($manager)
        ->getJson("/api/v1/units/{$unit->id}");

    $response
        ->assertOk()
        ->assertJsonFragment([
            'id' => $unit->id,
            'unit_number' => $unit->unit_number,
        ]);
});

it("prevents manager from viewing unit in unowned property", function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $manager2 = User::factory()->create();
    $manager2->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(['manager_id' => $manager->id]);

    $unit = Unit::factory()->create([
        'property_id' => $property->id
    ]);

    $response = $this
        ->actingAs($manager2)
        ->getJson("/api/v1/units/{$unit->id}");

    $response
        ->assertForbidden();
});
