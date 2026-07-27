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
use App\Services\Maintenance\DailyDigestService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Mail;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('queues a digest email for every manager', function () {
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
        'status' => WorkOrderStatusEnum::PENDING,
    ]);

    $count = app(DailyDigestService::class)
        ->execute();

    expect($count)->toBe(1);

    Mail::assertQueued(
        DailyMaintenanceDigestMail::class,
        fn($mail) =>

        $mail->hasTo($manager->email)
    );
});
