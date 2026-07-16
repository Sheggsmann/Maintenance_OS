<?php

namespace App\DTOs\Property;

use Spatie\LaravelData\Data;

final class UpdatePropertyData extends Data
{
    public function __construct(
        public ?string $name,
        public ?string $address,
        public ?string $city,
        public ?string $state,
        public ?string $country,
        public ?string $postal_code,
        public ?string $description,
        public ?int $manager_id,
        public ?bool $is_active,
    ) {}
}
