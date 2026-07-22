<?php

namespace App\Actions\MaintenanceRequest;

use App\Enums\MaintenanceStatusEnum;
use App\Models\MaintenanceRequest;
use Illuminate\Validation\ValidationException;

final class StartMaintenanceRequestAction
{
    public function __invoke(
        MaintenanceRequest $maintenanceRequest,
    ): MaintenanceRequest {

        if ($maintenanceRequest->status !== MaintenanceStatusEnum::ASSIGNED) {
            throw ValidationException::withMessages([
                'status' => 'Only assigned requests can be started.',
            ]);
        }

        $maintenanceRequest->update([
            'status' => MaintenanceStatusEnum::IN_PROGRESS,
            'started_at' => now(),
        ]);

        return $maintenanceRequest->load([
            'property',
            'unit',
            'tenant',
            'contractor',
        ]);
    }
}
