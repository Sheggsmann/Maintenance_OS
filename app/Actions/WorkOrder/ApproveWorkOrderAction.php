<?php

namespace App\Actions\WorkOrder;

use App\DTOs\WorkOrder\ApproveWorkOrderData;
use App\Enums\ApprovalDecisionEnum;
use App\Enums\ApprovalStageEnum;
use App\Models\ApprovalHistory;
use App\Models\User;
use App\Models\WorkOrder;
use App\Services\Approval\ApprovalPipelineService;
use Illuminate\Support\Facades\DB;

final class ApproveWorkOrderAction
{
    public function __construct(private ApprovalPipelineService $pipeline) {}

    public function __invoke(
        WorkOrder $workOrder,
        ApproveWorkOrderData $data,
        User $user
    ): WorkOrder {
        return DB::transaction(function () use ($workOrder, $data, $user) {
            ApprovalHistory::create([
                'work_order_id' => $workOrder->id,
                'approved_by' => $user->id,
                'stage' => ApprovalStageEnum::MANAGER_REVIEW->value,
                'decision' => ApprovalDecisionEnum::APPROVED->value,
                'reason' => $data->reason,
                'approved_at' => now(),
            ]);

            $this->pipeline->handle($workOrder);

            return $workOrder->fresh([
                'approvals',
                'contractor',
                'maintenanceRequest',
            ]);
        });
    }
}
