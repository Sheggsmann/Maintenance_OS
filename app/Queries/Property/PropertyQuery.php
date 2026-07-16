<?php

namespace App\Queries\Property;

use App\DTOs\Property\PropertyFilters;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;

final class PropertyQuery
{
    public function build(PropertyFilters $filters): Builder
    {
        return Property::query()
            ->with('manager')
            ->when(
                $filters->search,
                fn(Builder $query, string $search) =>
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                })
            )->when(
                $filters->city,
                fn(Builder $query, string $city) => $query->where('city', $city)
            )->when(
                $filters->state,
                fn(Builder $query, string $state) => $query->where('state', $state)
            )->when(
                $filters->manager_id,
                fn(Builder $query, int $manager_id)
                => $query->where('manager_id', $manager_id)
            )
            ->when(
                ! is_null($filters->is_active),
                fn(Builder $query)
                => $query->where('is_active', $filters->is_active)
            )
            ->orderBy(
                $filters->sort_by,
                $filters->sort_direction
            );
    }
}
