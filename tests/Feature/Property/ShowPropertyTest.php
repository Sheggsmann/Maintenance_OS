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


it('lets admin view any property', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $property = Property::factory()->create();

    $this
        ->actingAs($admin)
        ->getJson("/api/v1/properties/{$property->id}")
        ->assertOk()
        ->assertJsonFragment([
            'id' => $property->id,
        ]);
});


it("cannot view another manager's property", function () {
    $manager1 = User::factory()->create();
    $manager1->assignRole(RoleEnum::MANAGER->value);

    $manager2 = User::factory()->create();
    $manager2->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create(["manager_id" => $manager1->id]);

    $response = $this
        ->actingAs($manager2)
        ->getJson("/api/v1/properties/{$property->id}");

    $response->assertForbidden();
});

it("returns 404 for missing property", function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $response = $this->actingAs($admin)->getJson("/api/v1/properties/99999999");
    $response->assertStatus(404);
});
