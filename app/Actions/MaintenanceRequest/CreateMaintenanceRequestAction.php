<?php

namespace App\Actions\MaintenanceRequest;

use App\DTOs\MaintenanceRequest\CreateMaintenanceRequestData;
use App\Enums\MaintenancePriorityEnum;
use App\Enums\MaintenanceStatusEnum;
use App\Models\MaintenanceRequest;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

final class CreateMaintenanceRequestAction
{
    public function __invoke(
        CreateMaintenanceRequestData $data,
    ): MaintenanceRequest {

        $unit = Unit::query()
            ->with('property')
            ->findOrFail($data->unit_id);

        return MaintenanceRequest::create([
            'property_id' => $unit->property_id,
            'unit_id' => $unit->id,
            'tenant_id' => Auth::id(),
            'assigned_to' => null,
            'title' => $data->title,
            'description' => $data->description,
            'priority' => $data->priority
                ?? MaintenancePriorityEnum::MEDIUM,
            'status' => MaintenanceStatusEnum::OPEN,
            'category' => $data->category,
            'reported_at' => now(),
        ]);
    }
}
