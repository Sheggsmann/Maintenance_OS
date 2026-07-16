<?php

use App\Enums\RoleEnum;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('lets admin update any property', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->patchJson("/api/v1/properties/{$property->id}", [
            'name' => 'Updated Estate',
            'address' => 'Updated Address',
            'city' => 'Ibadan',
            'state' => 'Oyo',
            'country' => 'Nigeria',
            'manager_id' => $manager->id,
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('properties', [
        'id' => $property->id,
        'name' => 'Updated Estate'
    ]);
});


it("lets manager update their own property", function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(['manager_id' => $manager->id]);

    $response = $this
        ->actingAs($manager)
        ->patchJson("/api/v1/properties/{$property->id}", [
            'name' => 'Updated Estate',
            'address' => 'Updated Address',
            'city' => 'Ibadan',
            'state' => 'Oyo',
            'country' => 'Nigeria',
        ]);

    $response->assertOk();
    $this->assertDatabaseHas('properties', [
        'id' => $property->id,
        'name' => 'Updated Estate'
    ]);
});

it("cannot let manager update another property", function () {
    $manager1 = User::factory()->create();
    $manager1->assignRole(RoleEnum::MANAGER->value);

    $manager2 = User::factory()->create();
    $manager2->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(['manager_id' => $manager1->id]);

    $response = $this
        ->actingAs($manager2)
        ->patchJson("/api/v1/properties/{$property->id}", [
            'name' => 'Updated Estate',
        ]);

    $response->assertForbidden();
});

it("requires a property name", function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $property = Property::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->patchJson(
            "/api/v1/properties/{$property->id}",
            [
                'name' => '',
            ]
        );

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('name');
});

it("cannot let guest update property", function () {
    $property = Property::factory()->create();

    $response = $this
        ->patchJson("/api/v1/properties/{$property->id}", ['name' => '']);
    $response->assertUnauthorized();
});
