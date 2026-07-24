<?php

use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Jobs\DispatchWorkOrderJob;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Queue::fake();
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('allows a manager to approve a maintenance request', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN,
    ]);

    $this
        ->actingAs($manager)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/approve", [
            'scope_of_work' => 'Repair leaking pipe',
            'estimated_cost' => 50000,
            'estimated_hours' => 4,
            'scheduled_for' => now()->addDay()->toDateString(),
        ])
        ->assertOk();

    expect($request->fresh()->status)
        ->toBe(MaintenanceStatusEnum::APPROVED);
});

it('allows an admin to approve a maintenance request', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN,
    ]);

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/approve", [
            'scope_of_work' => 'Repair leaking pipe',
            'estimated_cost' => 50000,
            'estimated_hours' => 4,
            'scheduled_for' => now()->addDay()->toDateString(),
        ])
        ->assertOk();
});

it('prevents a tenant from approving a maintenance request', function () {
    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    $request = MaintenanceRequest::factory()->create();
    $this
        ->actingAs($tenant)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/approve", [
            'scope_of_work' => 'Repair leaking pipe',
            'estimated_cost' => 50000,
            'estimated_hours' => 4,
            'scheduled_for' => now()->addDay()->toDateString(),
        ])
        ->assertForbidden();
});

it('requires scope of work', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create();

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/approve", [
            'estimated_cost' => 500,
            'estimated_hours' => 2,
            'scheduled_for' => now()->addDay()->toDateString(),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('scope_of_work');
});

it('requires estimated cost', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create();

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/approve", [
            'scope_of_work' => 'Repair',
            'estimated_hours' => 2,
            'scheduled_for' => now()->addDay()->toDateString(),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('estimated_cost');
});

it('creates a work order', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN,
    ]);

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/approve", [
            'scope_of_work' => 'Repair',
            'estimated_cost' => 100,
            'estimated_hours' => 2,
            'scheduled_for' => now()->addDay()->toDateString(),
        ]);

    $this->assertDatabaseHas('work_orders', [
        'maintenance_request_id' => $request->id,
    ]);
});

it('dispatches the work order when the approval pipeline approves', function () {
    Queue::fake();

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::OPEN,
    ]);

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/approve", [
            'scope_of_work' => 'Repair',
            'estimated_cost' => 100,
            'estimated_hours' => 2,
            'scheduled_for' => now()->addDay()->toDateString(),
        ])->assertOk();

    Queue::assertPushed(DispatchWorkOrderJob::class);
});

it('does not allow approving an already approved request', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::APPROVED,
    ]);

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/approve", [
            'scope_of_work' => 'Repair',
            'estimated_cost' => 100,
            'estimated_hours' => 2,
            'scheduled_for' => now()->addDay()->toDateString(),
        ])
        ->assertForbidden();
});

it('does not allow approving a cancelled request', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceStatusEnum::CANCELLED,
    ]);

    $this
        ->actingAs($admin)
        ->postJson("/api/v1/maintenance-requests/{$request->id}/approve", [
            'scope_of_work' => 'Repair',
            'estimated_cost' => 100,
            'estimated_hours' => 2,
            'scheduled_for' => now()->addDay()->toDateString(),
        ])
        ->assertForbidden();
});
