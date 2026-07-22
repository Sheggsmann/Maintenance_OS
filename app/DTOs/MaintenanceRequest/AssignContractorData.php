<?php

namespace App\DTOs\MaintenanceRequest;

use Spatie\LaravelData\Data;

final class AssignContractorData extends Data
{
    public function __construct(
        public int $contractor_id,
    ) {}
}
