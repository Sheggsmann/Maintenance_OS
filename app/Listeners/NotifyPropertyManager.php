<?php

namespace App\Listeners;

use App\Events\WorkOrderCompleted;
use App\Notifications\WorkOrderCompletedNotification;

class NotifyPropertyManager
{
    public function __construct() {}

    public function handle(WorkOrderCompleted $event): void
    {
        // Send email / notification to manager
        $manager = $event->workOrder->maintenanceRequest->property->manager;

        if (!$manager) {
            return;
        }

        $manager->notify(new WorkOrderCompletedNotification($event->workOrder));

        // Send email / notification to tenant
        $tenant = $event->workOrder->maintenanceRequest->property->tenant;

        if (!$tenant) {
            return;
        }

        $tenant->notify(new WorkOrderCompletedNotification($event->workOrder));
    }
}
