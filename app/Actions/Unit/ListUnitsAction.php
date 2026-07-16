<?php

namespace App\Actions\Unit;

use App\DTOs\Units\UnitFilters;
use App\Queries\Unit\UnitQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ListUnitsAction
{
    public function __construct(
        private readonly UnitQuery $query,
    ) {}

    public function __invoke(
        UnitFilters $filters,
    ): LengthAwarePaginator {

        return $this
            ->query
            ->build($filters)
            ->paginate($filters->per_page)
            ->withQueryString();
    }
}
