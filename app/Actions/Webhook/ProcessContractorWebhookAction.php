<?php

namespace App\Actions\Webhook;

use App\DTOs\Webhook\ContractorWebhookData;
use App\Models\WorkOrder;

class ProcessContractorWebhookAction
{
    public function __invoke(
        ContractorWebhookData $data
    ): void {

        $workOrder = WorkOrder::where(
            'reference',
            $data->reference
        )->firstOrFail();

        match ($data->status) {
            'accepted' => $workOrder->accepted(),

            'in_progress' => $workOrder->start(),

            'completed' => $workOrder->complete(),

            default => null,
        };
    }
}
