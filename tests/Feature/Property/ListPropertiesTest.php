<?php

use App\Enums\RoleEnum;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('lets admin list all properties', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    Property::factory()->count(5)->create();

    $response = $this->actingAs($admin)->getJson('/api/v1/properties');
    $response->assertOk()->assertJsonCount(5, 'data');
});

it('lets manager only see assigned properties', function () {
    $managerOne = User::factory()->create();
    $managerOne->assignRole(RoleEnum::MANAGER->value);

    $managerTwo = User::factory()->create();
    $managerTwo->assignRole(RoleEnum::MANAGER->value);

    Property::factory()->count(2)->create([
        'manager_id' => $managerOne->id,
    ]);

    Property::factory()->count(3)->create([
        'manager_id' => $managerTwo->id,
    ]);

    $response = $this
        ->actingAs($managerOne)
        ->getJson('/api/v1/properties');

    $response
        ->assertOk()
        ->assertJsonCount(2, 'data');
});


it('can search properties by name', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    Property::factory()->create([
        'name' => 'Victoria Estate',
    ]);

    Property::factory()->create([
        'name' => 'Banana Island',
    ]);

    $response = $this
        ->actingAs($admin)
        ->getJson('/api/v1/properties?search=Victoria');

    $response
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'name' => 'Victoria Estate',
        ]);
});

it('returns paginated results', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    Property::factory()->count(30)->create();

    $response = $this
        ->actingAs($admin)
        ->getJson('/api/v1/properties?per_page=15');

    $response
        ->assertOk()
        ->assertJsonCount(15, 'data')
        ->assertJsonStructure([
            'data',
            // 'links',
            // 'meta',
        ]);
});

it('cannot let guest list properties', function () {
    $response = $this
        ->getJson('/api/v1/properties');

    $response
        ->assertUnauthorized();
});
