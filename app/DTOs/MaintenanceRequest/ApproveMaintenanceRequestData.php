<?php

namespace App\DTOs\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

final class ApproveMaintenanceRequestData extends Data
{
    public function __construct(
        public readonly MaintenanceRequest $maintenanceRequest,
        public readonly User $approvedBy,
        public readonly string $scopeOfWork,
        public readonly float $estimatedCost,
        public readonly float $estimatedHours,
        public readonly ?CarbonInterface $scheduledFor,
    ) {}
}
