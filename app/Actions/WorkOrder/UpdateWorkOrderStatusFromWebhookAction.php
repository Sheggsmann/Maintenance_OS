<?php

namespace App\Actions\WorkOrder;

use App\DTOs\Webhook\ContractorWebhookData;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UpdateWorkOrderStatusFromWebhookAction
{
    public function execute(
        ContractorWebhookData $data,
    ): WorkOrder {

        return DB::transaction(function () use ($data) {

            $workOrder = WorkOrder::findOrFail(
                $data->maintenanceWorkOrderId
            );

            /*
             * Prevent invalid backwards transitions
             */

            if (
                $workOrder->status === $data->status
            ) {
                return $workOrder;
            }

            if (
                $workOrder->status->value === 'completed'
            ) {
                throw new ConflictHttpException(
                    'Completed work orders cannot change status.'
                );
            }

            $workOrder->update([

                'status' => $data->status,

            ]);

            return $workOrder->fresh();
        });
    }
}
