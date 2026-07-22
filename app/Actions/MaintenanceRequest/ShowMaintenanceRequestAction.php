<?php

namespace App\Actions\MaintenanceRequest;

use App\Models\MaintenanceRequest;

final class ShowMaintenanceRequestAction
{
    public function __invoke(
        MaintenanceRequest $maintenanceRequest,
    ): MaintenanceRequest {
        return $maintenanceRequest->load(
            [
                'property',
                'unit',
                'tenant',
                'contractor',
            ]
        );
    }
}
