<?php

namespace App\DTOs\MaintenanceRequest;

use Spatie\LaravelData\Data;

final class CancelMaintenanceRequestData extends Data
{
    public function __construct(
        public string $reason,
    ) {}
}
