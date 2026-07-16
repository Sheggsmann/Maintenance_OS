<?php

namespace App\Queries\Unit;

use App\DTOs\Units\UnitFilters;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;

final class UnitQuery
{
    public function build(UnitFilters $filters): Builder
    {
        return Unit::query()
            ->with([
                'property',
                'tenant',
            ])

            ->when(
                $filters->search,
                fn($query, $search)

                => $query->where(
                    'unit_number',
                    'like',
                    "%{$search}%"
                )
            )

            ->when(
                $filters->property_id,
                fn($query, $property)

                => $query->where(
                    'property_id',
                    $property
                )
            )

            ->when(
                $filters->status,
                fn($query, $status)

                => $query->where(
                    'status',
                    $status
                )
            )

            ->when(
                $filters->type,
                fn($query, $type)

                => $query->where(
                    'type',
                    $type
                )
            )

            ->when(
                $filters->tenant_id,
                fn($query, $tenant)

                => $query->where(
                    'tenant_id',
                    $tenant
                )
            )

            ->orderBy(
                $filters->sort_by,
                $filters->sort_direction
            );
    }
}
