<?php

namespace App\Actions\MaintenanceRequest;

use App\DTOs\MaintenanceRequest\CancelMaintenanceRequestData;
use App\Enums\MaintenanceStatusEnum;
use App\Models\MaintenanceRequest;
use Illuminate\Validation\ValidationException;

final class CancelMaintenanceRequestAction
{
    public function __invoke(
        MaintenanceRequest $maintenanceRequest,
        CancelMaintenanceRequestData $data
    ): MaintenanceRequest {

        if ($maintenanceRequest->status === MaintenanceStatusEnum::COMPLETED) {
            throw ValidationException::withMessages([
                'status' => 'cannot cancel completed requests.',
            ]);
        }

        $maintenanceRequest->update([
            'status' => MaintenanceStatusEnum::CANCELLED,
            'cancellation_reason' => $data->reason,
            'cancelled_at' => now()
        ]);

        return $maintenanceRequest->load([
            'property',
            'unit',
            'tenant',
            'contractor',
        ]);
    }
}
