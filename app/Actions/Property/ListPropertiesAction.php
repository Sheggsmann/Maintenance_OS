<?php


namespace App\Actions\Property;

use App\DTOs\Property\PropertyFilters;
use App\Enums\RoleEnum;
use App\Queries\Property\PropertyQuery;
use Illuminate\Pagination\LengthAwarePaginator;

final class ListPropertiesAction
{
    public function __construct(private readonly PropertyQuery $query) {}

    public function __invoke(PropertyFilters $filters): LengthAwarePaginator
    {
        $query = $this->query->build($filters);

        if (auth()->check() && auth()->user()->hasRole(RoleEnum::MANAGER->value)) {
            $query->where('manager_id', auth()->id());
        }

        return $query
            ->paginate(perPage: $filters->per_page)
            ->withQueryString();
    }
}
