<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Webhook\ContractorWebhookData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractorWebhookRequest;
use App\Jobs\Contractor\ProcessContractorWebhookJob;
use App\Services\Webhook\HmacSignatureService;
use App\Services\Webhook\IdempotencyService;
use App\Utils\ApiResponse;
use Illuminate\Http\JsonResponse;

class ContractorWebhookController extends Controller
{
    public function __invoke(
        ContractorWebhookRequest $request,
        HmacSignatureService $signature,
        IdempotencyService $idempotency
    ): JsonResponse {

        $payload = $request->validated();

        $body = json_encode($payload);

        if (! $signature->verify(
            $body,
            $request->header('X-Timestamp'),
            $request->header('X-Signature')
        )) {
            return ApiResponse::error(
                message: 'Invalid signature',
                status: 401
            );
        }

        if (
            $idempotency->alreadyProcessed(
                $payload['event_id']
            )
        ) {
            return ApiResponse::error(
                message: "Webhook already processed",
                status: 401
            );
        }

        $idempotency->record($payload);

        ProcessContractorWebhookJob::dispatch(
            new ContractorWebhookData(
                eventId: $payload['event_id'],
                reference: $payload['reference'],
                status: $payload['status'],
                occurredAt: $payload['occurred_at'],
                payload: $payload,
            )
        );


        return ApiResponse::success(
            status: 202,
            message: "Webhook accepted"
        );
    }
}
