<?php

namespace App\Actions\Property;

use App\Models\Property;

final class ShowPropertyAction
{
    public function __invoke(Property $property): Property
    {
        return $property->load('manager');
    }
}
