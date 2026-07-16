<?php

namespace App\Actions\Unit;

use App\DTOs\Units\CreateUnitData;
use App\Models\Unit;

final class CreateUnitAction
{
    public function __invoke(CreateUnitData $data): Unit
    {
        return Unit::create([
            'property_id' => $data->property_id,
            'tenant_id' => $data->tenant_id,
            'unit_number' => $data->unit_number,
            'floor' => $data->floor,
            'type' => $data->type,
            'status' => $data->status,
            'bedrooms' => $data->bedrooms,
            'bathrooms' => $data->bathrooms,
            'area' => $data->area,
            'monthly_rent' => $data->monthly_rent,
            'description' => $data->description,
        ]);
    }
}
