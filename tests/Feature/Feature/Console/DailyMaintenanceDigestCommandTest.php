<?php

use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\WorkOrderStatusEnum;
use App\Mail\DailyMaintenanceDigestMail;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkOrder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Mail;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('sends one digest email to each manager', function () {
    Mail::fake();

    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create([
        'manager_id' => $manager->id,
    ]);

    $unit = Unit::factory()->create([
        'property_id' => $property->id,
    ]);

    $request = MaintenanceRequest::factory()->create([
        'property_id' => $property->id,
        'unit_id' => $unit->id,
        'status' => MaintenanceStatusEnum::OPEN,
    ]);

    WorkOrder::factory()->create([
        'maintenance_request_id' => $request->id,
        'status' => WorkOrderStatusEnum::DISPATCHED,
    ]);

    $this->artisan('maintenance:daily-digest')
        ->expectsOutput('Daily digest sent to 1 manager(s).')
        ->assertSuccessful();

    Mail::assertQueued(
        DailyMaintenanceDigestMail::class,
        fn($mail) =>

        $mail->hasTo($manager->email)
    );
});

it('does not send digest to non managers', function () {
    Mail::fake();

    User::factory()->create();

    $this->artisan('maintenance:daily-digest')
        ->expectsOutput('Daily digest sent to 0 manager(s).')
        ->assertSuccessful();

    Mail::assertNothingQueued();
});
