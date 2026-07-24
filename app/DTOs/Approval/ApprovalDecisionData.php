<?php

namespace App\DTOs\Approval;

class ApprovalDecisionData
{
    public function __construct(
        public readonly bool $approved,
        public readonly ?string $reason = null,
        public readonly array $metadata = [],
    ) {}

    public static function approved(array $metadata = []): self
    {
        return new self(
            approved: true,
            metadata: $metadata,
        );
    }

    public static function rejected(
        string $reason,
        array $metadata = [],
    ): self {
        return new self(
            approved: false,
            reason: $reason,
            metadata: $metadata,
        );
    }
}
