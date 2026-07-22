<?php

use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('lets admin view any maintenance request', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->getJson("/api/v1/maintenance-requests/{$request->id}");

    $response
        ->assertOk()
        ->assertJsonPath(
            'data.id',
            $request->id
        );
});
