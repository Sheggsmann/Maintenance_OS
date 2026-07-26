<?php


namespace App\Http\Controllers\Api\V1;

use App\Actions\WorkOrder\UpdateWorkOrderStatusFromWebhookAction;
use App\DTOs\Webhook\ContractorStatusWebhookData;
use App\DTOs\Webhook\ContractorWebhookData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Webhook\ContractorStatusWebhookRequest;
use App\Services\Webhook\HmacSignatureService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ContractorWebhookController extends Controller
{
    public function __construct(
        private readonly HmacSignatureService $signatureService,
    ) {}

    public function __invoke(
        ContractorStatusWebhookRequest $request,
        UpdateWorkOrderStatusFromWebhookAction $action,
    ) {

        if (
            $request->header('X-API-Key')
            !== config('contractor.api_key')
        ) {
            throw new AccessDeniedHttpException(
                'Invalid API key.'
            );
        }

        $payload = $request->getContent();

        $timestamp = $request->header('X-Timestamp');

        $signature = $request->header('X-Signature');

        if (! $this->signatureService->verify(
            payload: $payload,
            timestamp: $timestamp,
            signature: $signature,

        )) {

            throw new AccessDeniedHttpException(
                'Invalid webhook signature.'
            );
        }

        $action->execute(
            new ContractorWebhookData(
                maintenanceWorkOrderId: $request->integer('maintenance_work_order_id'),
                status: $request->enum(
                    'status',
                    \App\Enums\WorkOrderStatusEnum::class
                ),

            )

        );

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully.',
        ]);
    }
}
