<?php

namespace App\DTOs\MaintenanceRequest;


use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use App\Enums\MaintenanceStatusEnum;
use Spatie\LaravelData\Data;

final class MaintenanceRequestFilters extends Data
{
    public function __construct(

        public ?string $search,

        public ?int $property_id,

        public ?int $unit_id,

        public ?int $tenant_id,

        public ?int $contractor_id,

        public ?MaintenanceStatusEnum $status,

        public ?MaintenancePriorityEnum $priority,

        public ?MaintenanceCategoryEnum $category,

        public ?string $reported_from,

        public ?string $reported_to,

        public int $per_page = 15,

        public string $sort_by = 'reported_at',

        public string $sort_direction = 'desc',
    ) {}
}
