<?php

namespace App\DTOs\Units;

use Spatie\LaravelData\Data;

class AssignTenantToUnitData extends Data
{
    public function __construct(
        public readonly int $tenantId,
    ) {}
}
