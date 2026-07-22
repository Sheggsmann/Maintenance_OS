<?php

namespace App\Queries\MaintenanceRequest;

use App\DTOs\MaintenanceRequest\MaintenanceRequestFilters;
use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;

final class MaintenanceRequestQuery
{
    public function build(MaintenanceRequestFilters $filters, Authenticatable $user): Builder
    {

        $query = MaintenanceRequest::query()
            ->with([
                'property',
                'unit',
                'tenant',
                'contractor',
            ]);

        $this->applyVisibility($query, $user);
        $this->applyFilters($query, $filters);

        return $query;
    }

    private function applyVisibility(Builder $query, Authenticatable $user): void
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return;
        }

        if ($user->hasRole(RoleEnum::MANAGER->value)) {
            $query->whereHas('property', function ($query) use ($user) {
                $query->where(
                    'manager_id',
                    $user->id
                );
            });
            return;
        }

        if ($user->hasRole(RoleEnum::TENANT->value)) {
            $query->where(
                'tenant_id',
                $user->id
            );
            return;
        }

        if ($user->hasRole(RoleEnum::CONTRACTOR->value)) {
            $query->where(
                'assigned_to',
                $user->id
            );
        }
    }

    private function applyFilters(
        Builder $query,
        MaintenanceRequestFilters $filters,
    ): void {
        $this->search($query, $filters);
        $this->filterProperty($query, $filters);
        // $this->filterUnit($query, $filters);
        // $this->filterPriority($query, $filters);
        // $this->filterCategory($query, $filters);
        $this->filterStatus($query, $filters);
        $this->filterDateRange($query, $filters);
        $this->sort($query, $filters);
    }

    private function search(
        Builder $query,
        MaintenanceRequestFilters $filters,
    ): void {
        if (!$filters->search) {
            return;
        }

        $query->where(function ($query) use ($filters) {
            $query
                ->where(
                    'title',
                    'like',
                    "%{$filters->search}%"
                )
                ->orWhere(
                    'description',
                    'like',
                    "%{$filters->search}%"
                );
        });
    }

    private function filterDateRange(
        Builder $query,
        MaintenanceRequestFilters $filters
    ): void {
        if (!$filters->reported_from && !$filters->reported_to) {
            return;
        }

        if ($filters->reported_from) {
            $query
                ->when(
                    $filters->reported_from,
                    fn($query, $date)
                    => $query->whereDate(
                        'reported_at',
                        '>=',
                        $date
                    )
                );
        }

        if ($filters->reported_to) {
            $query->when(
                $filters->reported_to,
                fn($query, $date)
                => $query->whereDate(
                    'reported_at',
                    '<=',
                    $date
                )
            );
        }
    }

    private function filterProperty(
        Builder $query,
        MaintenanceRequestFilters $filters,
    ): void {
        if (!$filters->property_id) {
            return;
        }

        $query->where(
            'property_id',
            $filters->property_id
        );
    }

    private function filterStatus(
        Builder $query,
        MaintenanceRequestFilters $filters,
    ): void {
        if (!$filters->status) {
            return;
        }

        $query->where(
            'status',
            $filters->status
        );
    }

    private function sort(
        Builder $query,
        MaintenanceRequestFilters $filters,
    ): void {
        $query->orderBy(
            $filters->sort_by,
            $filters->sort_direction
        );
    }
}
