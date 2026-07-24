<?php

namespace App\Services\Approval;

use App\DTOs\Approval\ApprovalDecisionData;
use App\Models\WorkOrder;

class ApprovalPipelineService
{
    public function evaluate(
        WorkOrder $workOrder
    ): ApprovalDecisionData {

        /*
         |--------------------------------------------------------------------------
         | Budget Rule
         |--------------------------------------------------------------------------
         */

        if (
            $workOrder->estimated_cost > 500000
        ) {

            return ApprovalDecisionData::rejected(
                reason: 'Estimated cost exceeds automatic approval threshold.',
                metadata: [
                    'estimated_cost' => $workOrder->estimated_cost,
                    'limit' => 500000,
                ]
            );
        }

        /*
         |--------------------------------------------------------------------------
         | Contractor Availability
         |--------------------------------------------------------------------------
         */

        /*
         * We'll replace this stub once
         * Contractor Dispatch Service
         * becomes available.
         */

        $contractorsAvailable = true;

        if (! $contractorsAvailable) {
            return ApprovalDecisionData::rejected(
                reason: 'No contractors are currently available.'
            );
        }

        /*
         |--------------------------------------------------------------------------
         | Approved
         |--------------------------------------------------------------------------
         */

        return ApprovalDecisionData::approved();
    }
}
