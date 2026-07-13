<?php

use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it("can register a new tenant", function () {
    $response = $this->postJson('/api/v1/register', [
        'name' => 'Promise',
        'email' => 'promise@example.com',
        'phone' => '08088866150',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => "Tenant"
    ]);

    $response->assertCreated()->assertJsonStructure([
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

        ],
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'promise@example.com'
    ]);

    $user = User::first();
    expect($user->hasRole(RoleEnum::TENANT->value))->toBeTrue();
});

it("cannot register with an existing email", function () {
    User::factory()->create([
        'email' => 'promise@example.com'
    ]);

    $response = $this->postJson('/api/v1/register', [
        'name' => 'Promise',
        'email' => 'promise@example.com',
        'phone' => '08088866150',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => "Tenant"
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors('email');
});

it('requires matching passwords', function () {
    $response = $this->postJson('/api/v1/register', [
        'name' => 'Promise',
        'email' => 'promise@example.com',
        'phone' => '08088866150',
        'password' => 'password',
        'password_confirmation' => '123456',
        'role' => "Tenant"
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors('password');
});

it('requires required fields', function () {
    $response = $this->postJson('/api/v1/register', []);
    $response->assertStatus(422)->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('creates a sanctum token after registration', function () {
    $this->postJson('/api/v1/register', [
        'name' => 'Promise',
        'email' => 'promise@example.com',
        'phone' => '08088866150',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => "Tenant"
    ]);

    $user = User::first();
    expect($user->tokens)->toHaveCount(1);
});
