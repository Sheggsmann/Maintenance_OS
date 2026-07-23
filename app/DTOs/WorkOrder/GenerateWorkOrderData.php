<?php

namespace App\DTOs\WorkOrder;

use Carbon\Carbon;
use App\Http\Requests\WorkOrder\GenerateWorkOrderRequest;

class GenerateWorkOrderData
{
    public function __construct(

        public readonly string $scopeOfWork,

        public readonly ?float $estimatedCost,

        public readonly ?int $estimatedHours,

        public readonly ?Carbon $scheduledFor,

    ) {}

    public static function fromRequest(
        GenerateWorkOrderRequest $request
    ): self {

        return new self(

            scopeOfWork: $request->string('scope_of_work'),

            estimatedCost: $request->estimated_cost,

            estimatedHours: $request->estimated_hours,

            scheduledFor: $request->scheduled_for
                ? Carbon::parse($request->scheduled_for)
                : null,
        );
    }
}
