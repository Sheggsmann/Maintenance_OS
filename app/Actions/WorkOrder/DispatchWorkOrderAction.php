<?php

namespace App\Actions\WorkOrder;

use App\Enums\DispatchStatusEnum;
use App\Models\ContractorDispatch;
use App\Models\WorkOrder;
use App\Services\Contractor\ContractorApiService;
use App\Services\Contractor\ContractorPayloadBuilder;

final class DispatchWorkOrderAction
{
    public function __construct(private ContractorApiService $api) {}

    public function __invoke(WorkOrder $workOrder): void
    {
        /*
        | 1. Call Contractor API
        | 2. Save dispatch attempt
        | 3. Update Work Order
        */

        $response = $this->api->dispatch($workOrder->load([
            'maintenanceRequest.property',
            'maintenanceRequest.unit',
            'maintenanceRequest.media',
        ]));

        // dd($response);
        ContractorDispatch::create([
            'work_order_id' => $workOrder->id,

            'attempt' => $workOrder->dispatches()->count() + 1,

            'status' => $response->status
                ?? DispatchStatusEnum::FAILED->value,

            'external_reference' => $response->externalReference,

            'request_payload' => app(
                ContractorPayloadBuilder::class
            )->build($workOrder),

            'response_payload' => $response->response,

            'error' => $response->success
                ? null
                : ($response->response['message'] ?? 'Dispatch failed'),

            'dispatched_at' => now(),
        ]);

        if ($response->success) {
            $workOrder->dispatch();
        }
    }
}
