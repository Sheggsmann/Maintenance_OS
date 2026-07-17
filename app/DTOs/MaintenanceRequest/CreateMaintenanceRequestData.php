<?php

namespace App\DTOs\MaintenanceRequest;

use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use Spatie\LaravelData\Data;

final class CreateMaintenanceRequestData extends Data
{
    public function __construct(
        public int $unit_id,
        public string $title,
        public string $description,
        public ?MaintenancePriorityEnum $priority,
        public MaintenanceCategoryEnum $category,
    ) {}
}
