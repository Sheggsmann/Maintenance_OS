<?php

namespace App\Actions\Unit;

use App\DTOs\Units\AssignTenantToUnitData;
use App\Enums\RoleEnum;
use App\Enums\UnitStatusEnum;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AssignTenantToUnitAction
{
    public function execute(
        Unit $unit,
        AssignTenantToUnitData $data,
    ): Unit {

        return DB::transaction(function () use (
            $unit,
            $data,
        ) {
            $tenant = User::find($data->tenantId);

            if (! $tenant) {
                throw new NotFoundHttpException(
                    'Tenant not found.'
                );
            }

            if (! $tenant->hasRole(RoleEnum::TENANT->value)) {
                throw new ConflictHttpException(
                    'Selected user is not a tenant.'
                );
            }

            if ($unit->tenant_id !== null) {
                throw new ConflictHttpException(
                    'Unit already has a tenant.'
                );
            }

            if (Unit::where('tenant_id', $tenant->id)->exists()) {
                throw new ConflictHttpException(
                    'Tenant already occupies another unit.'
                );
            }

            $unit->update([
                'tenant_id' => $tenant->id,
                'status' => UnitStatusEnum::OCCUPIED
            ]);



            return $unit->fresh();
        });
    }
}
