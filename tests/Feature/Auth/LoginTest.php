<?php

use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it("can login a user", function () {
    $user = User::factory()->create([
        'email' => 'promise@example.com',
        'password' => bcrypt('password')
    ]);

    $user->assignRole(RoleEnum::TENANT->value);

    $response = $this->postJson('/api/v1/login', [
        'email' => 'promise@example.com',
        'password' => 'password'
    ]);

    $response->assertStatus(200)->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'token',

            'user' => [

                'id',

                'name',

                'email',

                'phone',

                'role',

                'created_at',

            ],
        ]
    ]);

    expect($user->fresh()->tokens)->toHaveCount(1);
});


it("cannot login with invalid credentials", function () {
    User::factory()->create([
        'email' => 'promise@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email' => 'promise@example.com',
        'password' => 'wrong_password'
    ]);

    $response->assertUnauthorized();
});

it("cannot login with an unknown email", function () {
    $response = $this->postJson('api/v1/login', [
        'email' => 'unknown@example.com',
        'password' => 'password',
    ]);

    $response
        ->assertUnauthorized();
});

it('cannot login when account is inactive', function () {
    User::factory()->create([
        'email' => 'promise@example.com',
        'password' => bcrypt('password'),
        'is_active' => false,
    ]);

    $response = $this->postJson('api/v1/login', [
        'email' => 'promise@example.com',
        'password' => 'password',
    ]);

    $response
        ->assertForbidden();
});
