<?php

namespace App\Actions\Property;

use App\DTOs\Property\UpdatePropertyData;
use App\Models\Property;


final class UpdatePropertyAction
{
    public function __invoke(Property $property, UpdatePropertyData $data): Property
    {
        $payload = array_filter($data->toArray(), fn($value) => !is_null($value));
        if ($payload !== []) {
            $property->fill($payload);
            $property->save();
        }

        return $property->fresh()->load('manager');
    }
}
