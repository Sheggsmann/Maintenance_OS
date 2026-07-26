<?php

namespace App\DTOs\Webhook;

use App\Enums\WorkOrderStatusEnum;
use Spatie\LaravelData\Data;

final class ContractorWebhookData extends Data
{
    public function __construct(
        // public readonly string $eventId,
        // public readonly string $reference,
        // public readonly string $status,
        // public readonly string $occurredAt,
        // public readonly array $payload,

        public readonly int $maintenanceWorkOrderId,
        public readonly WorkOrderStatusEnum $status,
    ) {}
}
