<?php

use App\Enums\RoleEnum;
use App\Enums\UnitStatusEnum;
use App\Enums\UnitTypeEnum;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('lets admin create a unit', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $property = Property::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->postJson(
            "/api/v1/units",
            [
                'property_id' => $property->id,
                'unit_number' => 'A101',
                'floor' => 1,
                'type' => UnitTypeEnum::APARTMENT->value,
                'status' => UnitStatusEnum::VACANT->value,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 120.5,
                'monthly_rent' => 450000,
            ]
        );

    $response
        ->assertCreated()
        ->assertJsonFragment([
            'unit_number' => 'A101',
        ]);

    $this->assertDatabaseHas('units', [
        'unit_number' => 'A101'
    ]);
});

it("lets managers create units inside owned properties", function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(['manager_id' => $manager->id]);

    $response = $this
        ->actingAs($manager)
        ->postJson(
            "/api/v1/units",
            [
                'property_id' => $property->id,
                'unit_number' => 'A101',
                'floor' => 1,
                'type' => UnitTypeEnum::APARTMENT->value,
                'status' => UnitStatusEnum::VACANT->value,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 120.5,
                'monthly_rent' => 450000,
            ]
        );

    $response
        ->assertCreated()
        ->assertJsonFragment([
            'unit_number' => 'A101',
        ]);

    $this->assertDatabaseHas('units', [
        'unit_number' => 'A101'
    ]);
});

it("prevents manager from creating units inside unowned property", function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $manager2 = User::factory()->create();
    $manager2->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(['manager_id' => $manager->id]);

    $response = $this
        ->actingAs($manager2)
        ->postJson(
            "/api/v1/units",
            [
                'property_id' => $property->id,
                'unit_number' => 'A101',
                'floor' => 1,
                'type' => UnitTypeEnum::APARTMENT->value,
                'status' => UnitStatusEnum::VACANT->value,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 120.5,
                'monthly_rent' => 450000,
            ]
        );

    $response->assertForbidden();
});

it("prevents duplicate unit number inside same property", function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(['manager_id' => $manager->id]);

    $this
        ->actingAs($manager)
        ->postJson(
            "/api/v1/units",
            [
                'property_id' => $property->id,
                'unit_number' => 'A101',
                'floor' => 1,
                'type' => UnitTypeEnum::APARTMENT->value,
                'status' => UnitStatusEnum::VACANT->value,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 120.5,
                'monthly_rent' => 450000,
            ]
        );

    $response = $this
        ->actingAs($manager)
        ->postJson(
            "/api/v1/units",
            [
                'property_id' => $property->id,
                'unit_number' => 'A101',
                'floor' => 2,
                'type' => UnitTypeEnum::APARTMENT->value,
                'status' => UnitStatusEnum::VACANT->value,
                'bedrooms' => 5,
                'bathrooms' => 5,
                'area' => 120.5,
                'monthly_rent' => 50000,
            ]
        );

    $response
        ->assertUnprocessable()
        ->assertJsonStructure(['errors']);
});
