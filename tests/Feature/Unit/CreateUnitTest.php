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

it("lets managers create units inside owned properties", function () {});

it("prevents manager from creating units inside unowned property", function () {});

it("prevents duplicate unit number inside same property", function () {});

it("prevents guest from creating units", function () {});
