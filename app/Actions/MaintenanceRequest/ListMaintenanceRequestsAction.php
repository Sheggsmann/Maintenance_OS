<?php

namespace App\Actions\MaintenanceRequest;

use App\DTOs\MaintenanceRequest\MaintenanceRequestFilters;
use App\Queries\MaintenanceRequest\MaintenanceRequestQuery;
use Illuminate\Contracts\Auth\Authenticatable;

final class ListMaintenanceRequestsAction
{
    public function __construct(
        private readonly MaintenanceRequestQuery $query,
    ) {}

    public function __invoke(
        MaintenanceRequestFilters $filters,
        Authenticatable $user,
    ) {
        return $this
            ->query
            ->build($filters, $user)
            ->paginate($filters->per_page)
            ->withQueryString();
    }
}
