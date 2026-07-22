<?php

namespace App\Actions\MaintenanceRequest;

use App\Enums\MaintenanceStatusEnum;
use App\Models\MaintenanceRequest;
use Illuminate\Validation\ValidationException;

final class CompleteMaintenanceRequestAction
{
    public function __invoke(
        MaintenanceRequest $maintenanceRequest,
    ): MaintenanceRequest {

        if ($maintenanceRequest->status !== MaintenanceStatusEnum::IN_PROGRESS) {
            throw ValidationException::withMessages([
                'status' => 'Only in-progress requests can be started.',
            ]);
        }

        if (! $maintenanceRequest->started_at) {
            throw ValidationException::withMessages([
                'started_at' => 'Maintenance request has not been started.',
            ]);
        }


        $maintenanceRequest->update([
            'status' => MaintenanceStatusEnum::COMPLETED,
            'completed_at' => now(),
        ]);

        return $maintenanceRequest->load([
            'property',
            'unit',
            'tenant',
            'contractor',
        ]);
    }
}
