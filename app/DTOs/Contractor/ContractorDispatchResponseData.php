<?php

namespace App\DTOs\Contractor;

use Spatie\LaravelData\Data;

class ContractorDispatchResponseData extends Data
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $externalReference,
        public readonly ?string $status,
        public readonly array $response,
    ) {}
}
