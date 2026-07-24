<?php

namespace App\Actions\MaintenanceRequest;

use App\Actions\WorkOrder\GenerateWorkOrderAction;
use App\DTOs\MaintenanceRequest\ApproveMaintenanceRequestData;
use App\DTOs\WorkOrder\GenerateWorkOrderData;
use App\Jobs\DispatchWorkOrderJob;
use App\Models\WorkOrder;
use App\Services\Approval\ApprovalPipelineService;
use Illuminate\Support\Facades\DB;

class ApproveMaintenanceRequestAction
{
    public function __construct(
        private readonly GenerateWorkOrderAction $generateWorkOrderAction,
        private readonly ApprovalPipelineService $approvalPipeline
    ) {}


    public function __invoke(
        ApproveMaintenanceRequestData $data
    ): WorkOrder {
        return DB::transaction(function () use ($data) {

            /*
             * Step 1
             * Approve request
             */
            $data->maintenanceRequest->approve($data->approvedBy);

            /*
             * Step 2
             * Generate Work Order
             */
            $workOrder = $this->generateWorkOrderAction->execute(
                $data->maintenanceRequest,
                new GenerateWorkOrderData(
                    scopeOfWork: $data->scopeOfWork,
                    estimatedCost: $data->estimatedCost,
                    estimatedHours: $data->estimatedHours,
                    scheduledFor: $data->scheduledFor,
                ),
                $data->approvedBy,
            );

            /*
             * Step 3
             * Evaluate approval pipeline
             */
            $decision = $this->approvalPipeline
                ->evaluate($workOrder);

            /*
             * Step 4
             * Dispatch if approved
             */
            if ($decision->approved) {
                DispatchWorkOrderJob::dispatch(
                    $workOrder
                );
            }

            return $workOrder;
        });
    }
}
