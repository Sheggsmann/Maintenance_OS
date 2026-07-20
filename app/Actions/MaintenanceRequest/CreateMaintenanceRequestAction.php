<?php

namespace App\Actions\MaintenanceRequest;

use App\DTOs\MaintenanceRequest\CreateMaintenanceRequestData;
use App\Enums\MaintenancePriorityEnum;
use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\UnitStatusEnum;
use App\Models\MaintenanceRequest;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class CreateMaintenanceRequestAction
{
    public function __invoke(
        CreateMaintenanceRequestData $data,
    ): MaintenanceRequest {

        $user = Auth::user();

        $unit = Unit::query()
            ->with('property')
            ->findOrFail($data->unit_id);

        if ($unit->status !== UnitStatusEnum::OCCUPIED) {
            throw ValidationException::withMessages(['unit_id' =>
            ['Only occupied units can receive maintenance requests']]);
        }

        if (
            $user->hasRole(RoleEnum::MANAGER->value)
            && $unit->property->manager_id !== $user->id
        ) {
            throw ValidationException::withMessages(['unit_id' =>
            ['You cannot create requests for properties you do not manage']]);
        }

        if (
            $user->hasRole(RoleEnum::TENANT->value) &&
            $unit->tenant_id !== $user->id
        ) {
            throw ValidationException::withMessages(['unit_id' =>
            ['You can only report maintenance issues for your own unit']]);
        }

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
