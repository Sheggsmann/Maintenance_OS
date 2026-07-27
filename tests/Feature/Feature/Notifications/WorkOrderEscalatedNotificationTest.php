<?php

use App\Models\User;
use App\Models\WorkOrder;
use App\Notifications\WorkOrderEscalatedNotification;
use Illuminate\Support\Facades\Notification;

it('sends escalation notification to manager', function () {
    Notification::fake();

    $manager = User::factory()->create();

    $workOrder = WorkOrder::factory()->create();

    $manager->notify(
        new WorkOrderEscalatedNotification($workOrder)
    );

    Notification::assertSentTo(
        $manager,
        WorkOrderEscalatedNotification::class
    );
});
