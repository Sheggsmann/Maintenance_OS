<?php

namespace App\DTOs\Units;

use App\Enums\UnitStatusEnum;
use App\Enums\UnitTypeEnum;
use Spatie\LaravelData\Data;

final class CreateUnitData extends Data
{
    public function __construct(
        public int $property_id,
        public ?int $tenant_id,
        public string $unit_number,
        public ?int $floor,
        public UnitTypeEnum $type,
        public UnitStatusEnum $status,
        public ?int $bedrooms,
        public ?int $bathrooms,
        public ?float $area,
        public ?float $monthly_rent,
        public ?string $description,
    ) {}
}
