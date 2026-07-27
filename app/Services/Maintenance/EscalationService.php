<?php

namespace App\Services\Maintenance;

use App\Enums\MaintenancePriorityEnum;
use App\Enums\WorkOrderStatusEnum;
use App\Models\WorkOrder;
use App\Notifications\WorkOrderEscalatedNotification;

class EscalationService
{
    public function execute(): int
    {
        $workOrders = WorkOrder::query()
            ->where(
                'status',
                WorkOrderStatusEnum::DISPATCHED
            )

            ->whereHas(
                'maintenanceRequest',
                fn($query) =>

                $query->where(
                    'priority',
                    MaintenancePriorityEnum::HIGH
                )
            )

            ->where(
                'updated_at',
                '<=',
                now()->subHours(4)
            )

            ->with([
                'maintenanceRequest.property.manager',
            ])

            ->get();

        foreach ($workOrders as $workOrder) {

            $manager = optional(
                $workOrder
                    ->maintenanceRequest
                    ->property
            )->manager;

            if (! $manager) {
                continue;
            }

            $manager->notify(

                new WorkOrderEscalatedNotification(
                    $workOrder
                )

            );
        }

        return $workOrders->count();
    }
}
