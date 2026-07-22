<?php

use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('lets admin view all maintenance requests', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    MaintenanceRequest::factory()->count(10)->create();

    $response = $this
        ->actingAs($admin)
        ->getJson('/api/v1/maintenance-requests');

    $response
        ->assertOk()
        ->assertJsonCount(10, 'data');
});
