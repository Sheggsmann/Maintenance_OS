<?php

use App\Enums\RoleEnum;
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

it('lets manager view units in their property', function () {});

it("prevents manager from viewing unit in unowned property", function () {});

it("prevents guest from viewing unit", function () {});
