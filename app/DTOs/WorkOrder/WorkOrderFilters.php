<?php

namespace App\DTOs\WorkOrder;

use Spatie\LaravelData\Data;

class WorkOrderFilters extends Data
{
    public function __construct(
        public readonly ?string $search,
        public readonly ?string $status,
        public readonly ?int $propertyId,
        public readonly ?int $managerId,
        public readonly int $perPage = 15,
    ) {}
}
