<?php

namespace App\Actions\MaintenanceRequest;

use App\DTOs\MaintenanceRequest\AssignContractorData;
use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

final class AssignContractorAction
{
    public function __invoke(
        MaintenanceRequest $request,
        AssignContractorData $data,
    ): MaintenanceRequest {

        $contractor = User::findOrFail(
            $data->contractor_id
        );

        if (! $contractor->hasRole(RoleEnum::CONTRACTOR->value)) {
            throw ValidationException::withMessages([
                'contractor_id' => 'Selected user is not a contractor.',
            ]);
        }

        if ($request->status !== MaintenanceStatusEnum::OPEN) {
            throw ValidationException::withMessages([
                'status' => 'Only open requests can be assigned.',
            ]);
        }

        if ($request->assigned_to !== null) {
            throw ValidationException::withMessages([
                'assigned_to' => 'Request already assigned.',
            ]);
        }

        $request->update([
            'assigned_to' => $contractor->id,
            'status' => MaintenanceStatusEnum::ASSIGNED
        ]);

        return $request->load([
            'property',
            'unit',
            'tenant',
            'contractor',
        ]);
    }
}
