<?php

use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\User;
use App\Models\WorkOrder;
use App\Notifications\WorkOrderCompletedNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('ensures manager receives notification', function () {
    Notification::fake();

    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    $property = Property::factory()->create([
        'manager_id' => $manager->id,
    ]);

    $request = MaintenanceRequest::factory()->create([
        'property_id' => $property->id,
    ]);

    $workOrder = WorkOrder::factory()->create([
        'maintenance_request_id' => $request->id,
    ]);

    Notification::send(
        $manager,
        new WorkOrderCompletedNotification($workOrder)
    );

    Notification::assertSentTo(
        $manager,
        WorkOrderCompletedNotification::class
    );
});

it('ensures notification includes property name', function () {
    $property = Property::factory()->make([
        'name' => 'Sunrise Estate',
    ]);

    $request = MaintenanceRequest::factory()->make();

    $request->setRelation('property', $property);

    $workOrder = WorkOrder::factory()->make();

    $workOrder->setRelation(
        'maintenanceRequest',
        $request
    );

    $notification = new WorkOrderCompletedNotification($workOrder);

    $mail = $notification->toMail(
        User::factory()->make()
    );

    expect($mail->introLines)
        ->toContain('Property: Sunrise Estate');
});
