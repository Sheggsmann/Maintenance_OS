<?php

namespace App\DTOs\Property;

use Spatie\LaravelData\Data;

final class PropertyFilters extends Data
{
    public function __construct(
        public ?string $search,
        public ?string $city,
        public ?string $state,
        public ?bool $is_active,
        public ?int $manager_id,
        public int $per_page = 15,
        public string $sort_by = 'created_at',
        public string $sort_direction = 'desc',
    ) {}
}
