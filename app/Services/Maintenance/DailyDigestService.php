<?php

namespace App\Services\Maintenance;

use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\WorkOrderStatusEnum;
use App\Mail\DailyMaintenanceDigestMail;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Mail;

class DailyDigestService
{
    public function execute(): int
    {
        $managers = User::query()
            ->role(RoleEnum::MANAGER->value)
            ->with('managedProperties')
            ->get();

        foreach ($managers as $manager) {

            $propertyIds = $manager
                ->managedProperties
                ->pluck('id');

            $maintenanceRequests = MaintenanceRequest::query()
                ->whereIn('property_id', $propertyIds)
                ->get();

            $workOrders = WorkOrder::query()
                ->whereHas(
                    'maintenanceRequest',
                    fn($query) => $query->whereIn(
                        'property_id',
                        $propertyIds
                    )
                )
                ->get();

            $digest = [
                'pendingRequests' => $maintenanceRequests
                    ->where(
                        'status',
                        MaintenanceStatusEnum::OPEN
                    )
                    ->count(),

                'pendingWorkOrders' => $workOrders
                    ->where(
                        'status',
                        WorkOrderStatusEnum::PENDING
                    )
                    ->count(),

                'dispatchedWorkOrders' => $workOrders
                    ->where(
                        'status',
                        WorkOrderStatusEnum::DISPATCHED
                    )
                    ->count(),

                'acceptedWorkOrders' => $workOrders
                    ->where(
                        'status',
                        WorkOrderStatusEnum::ACCEPTED
                    )
                    ->count(),

                'inProgressWorkOrders' => $workOrders
                    ->where(
                        'status',
                        WorkOrderStatusEnum::IN_PROGRESS
                    )
                    ->count(),

                'completedToday' => $workOrders
                    ->filter(
                        fn($workOrder) =>
                        $workOrder->status === WorkOrderStatusEnum::COMPLETED
                            && $workOrder->updated_at->isToday()
                    )
                    ->count(),

                'cancelledToday' => $workOrders
                    ->filter(
                        fn($workOrder) =>
                        $workOrder->status === WorkOrderStatusEnum::CANCELLED
                            && $workOrder->updated_at->isToday()
                    )
                    ->count(),
            ];

            Mail::to($manager)
                ->queue(
                    new DailyMaintenanceDigestMail(
                        manager: $manager,
                        digest: $digest,
                    )
                );
        }

        return $managers->count();
    }
}
