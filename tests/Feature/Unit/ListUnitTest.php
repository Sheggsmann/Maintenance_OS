<?php

use App\Enums\RoleEnum;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('lets admin list all units', function () {
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

it("lets manager see units in managed properties only", function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $manager2 = User::factory()->create();
    $manager2->assignRole(RoleEnum::MANAGER->value);

    $property1 = Property::factory()->create(['manager_id' => $manager->id]);

    // create a 2nd property
    Property::factory()->create(['manager_id' => $manager2->id]);

    Unit::factory()->create(['property_id' => $property1->id]);

    $response = $this
        ->actingAs($manager)
        ->getJson("/api/v1/units");

    $response
        ->assertOk()
        ->assertJsonCount(1, 'data');
});
