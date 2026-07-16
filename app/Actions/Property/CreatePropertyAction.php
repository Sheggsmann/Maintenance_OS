<?php

namespace App\Actions\Property;

use App\DTOs\Property\CreatePropertyData;
use App\Models\Property;

final class CreatePropertyAction
{
    public function __invoke(CreatePropertyData $data): Property
    {
        return Property::create([
            'name' => $data->name,
            'address' => $data->address,
            'city' => $data->city,
            'state' => $data->state,
            'country' => $data->country ?? 'Nigeria',
            'postal_code' => $data->postal_code,
            'description' => $data->description,
            'manager_id' => $data->manager_id
        ]);
    }
}
