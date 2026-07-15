<?php

use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('admin can create a property', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $response = $this->actingAs($admin)->postJson('/api/v1/properties', [
        'name' => 'Victoria Estate',
        'address' => '12 Admiralty Way',
        'city' => 'Lekki',
        'state' => 'Lagos',
        'country' => 'Nigeria',
        'manager_id' => $manager->id,
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('properties', [
        'name' => 'Victoria Estate',
    ]);
});
