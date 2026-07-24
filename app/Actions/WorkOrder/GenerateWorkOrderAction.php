<?php

namespace App\Actions\WorkOrder;

use App\DTOs\WorkOrder\GenerateWorkOrderData;
use App\Enums\MaintenanceStatusEnum;
use App\Enums\WorkOrderStatusEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class GenerateWorkOrderAction
{
    public function execute(
        MaintenanceRequest $maintenanceRequest,
        GenerateWorkOrderData $data,
        User $user
    ): WorkOrder {
        if (! in_array($maintenanceRequest->status, [
            MaintenanceStatusEnum::OPEN,
            MaintenanceStatusEnum::APPROVED,
        ], true)) {
            throw new ConflictHttpException(
                'Only OPEN or APPROVED maintenance requests can generate work orders.'
            );
        }

        if ($maintenanceRequest->workOrder()->exists()) {
            throw new ConflictHttpException(
                'A work order already exists for this maintenance request.'
            );
        }

        return DB::transaction(function () use (
            $maintenanceRequest,
            $data,
            $user
        ) {

            // Work Order should be approved
            $workOrder =  WorkOrder::create([
                'maintenance_request_id' => $maintenanceRequest->id,
                'created_by' => $user->id,
                'reference' => $this->generateReference(),
                'scope_of_work' => $data->scopeOfWork,
                'estimated_cost' => $data->estimatedCost,
                'estimated_hours' => $data->estimatedHours,
                'scheduled_for' => $data->scheduledFor,
                'status' => WorkOrderStatusEnum::APPROVED->value,
            ]);

            $workOrder->update([
                'reference' => sprintf(
                    'WO-%s-%05d',
                    now()->format('Ymd'),
                    $workOrder->id
                ),
            ]);

            return $workOrder->fresh();
        });
    }

    private function generateReference(): string
    {
        $count = WorkOrder::count('*') + 1;

        return sprintf(
            'WO-%s-%05d',
            now()->format('Ymd'),
            $count
        );
    }
}
