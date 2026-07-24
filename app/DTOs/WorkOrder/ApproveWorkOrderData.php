<?php

namespace App\DTOs\WorkOrder;

use Spatie\LaravelData\Data;

final class ApproveWorkOrderData extends Data
{
    public function __construct(
        public readonly ?string $reason,
    ) {}
}
