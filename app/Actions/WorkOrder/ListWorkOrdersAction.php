<?php

namespace App\Actions\WorkOrder;

use App\DTOs\WorkOrder\WorkOrderFilters;
use App\Models\WorkOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListWorkOrdersAction
{
    public function execute(
        WorkOrderFilters $filters,
    ): LengthAwarePaginator {

        return WorkOrder::query()
            ->with([
                'maintenanceRequest',
                'maintenanceRequest.property.manager',
                'maintenanceRequest.unit',
            ])

            ->when(
                $filters->search,
                fn($query) =>
                $query->where(
                    'reference',
                    'like',
                    "%{$filters->search}%"
                )
            )

            ->when(
                $filters->status,
                fn($query) =>
                $query->where(
                    'status',
                    $filters->status
                )
            )

            ->when(
                $filters->propertyId,
                fn($query) =>
                $query->whereHas(
                    'maintenanceRequest',
                    fn($q) =>
                    $q->where(
                        'property_id',
                        $filters->propertyId
                    )
                )
            )

            ->when(
                $filters->managerId,
                fn($query) =>
                $query->whereHas(
                    'maintenanceRequest.property',
                    fn($q) =>
                    $q->where(
                        'manager_id',
                        $filters->managerId
                    )
                )
            )

            ->latest()
            ->paginate(
                $filters->perPage
            );
    }
}
