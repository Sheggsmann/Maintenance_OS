<?php

use App\Enums\RoleEnum;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('can let admin delete any property', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $property = Property::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->deleteJson("/api/v1/properties/{$property->id}");

    $response->assertOk();

    $this->assertSoftDeleted('properties', [
        'id' => $property->id,
    ]);
});
