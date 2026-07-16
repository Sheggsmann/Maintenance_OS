<?php

namespace App\DTOs\Units;

use App\Enums\UnitStatusEnum;
use App\Enums\UnitTypeEnum;
use Spatie\LaravelData\Data;

final class UnitFilters extends Data
{
    public function __construct(
        public ?string $search,

        public ?int $property_id,

        public ?UnitStatusEnum $status,

        public ?UnitTypeEnum $type,

        public ?int $tenant_id,

        public int $per_page = 15,

        public string $sort_by = 'created_at',

        public string $sort_direction = 'desc',
    ) {}
}
