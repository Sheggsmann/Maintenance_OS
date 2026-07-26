<?php

namespace App\Services\Contractor;

use App\DTOs\Contractor\ContractorDispatchResponseData;
use App\Enums\DispatchStatusEnum;
use App\Models\WorkOrder;
use App\Services\Webhook\HmacSignatureService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class ContractorApiService
{
    public function __construct(
        private ContractorPayloadBuilder $builder,
        private HmacSignatureService $signature
    ) {}

    public function dispatch(WorkOrder $workOrder): ContractorDispatchResponseData
    {
        $payload = $this->builder->build($workOrder);
        $body = json_encode($payload);
        $timestamp = now()->timestamp;

        try {
            $response = Http::timeout(config('contractor.timeout'))
                ->retry(config('contractor.retry_times'), 1000, fn() => true, true)
                ->withHeaders([
                    'X-API-Key' => config('contractor.api_key'),

                    'X-Timestamp' => $timestamp,

                    'X-Signature' => $this->signature->generate(
                        $body,
                        $timestamp
                    ),
                ])
                ->post(config('contractor.base_url') . '/work-orders', $payload);

            return new ContractorDispatchResponseData(
                success: $response->successful(),
                externalReference: $response->json('external_reference'),
                status: $response->successful()
                    ? DispatchStatusEnum::SENT->value
                    : DispatchStatusEnum::FAILED->value,
                response: $response->json(),
            );
        } catch (RequestException | ConnectionException $e) {
            return new ContractorDispatchResponseData(
                success: false,
                externalReference: null,
                status: DispatchStatusEnum::FAILED->value,
                response: [
                    'message' => $e->getMessage()
                ]
            );
        }
    }
}
