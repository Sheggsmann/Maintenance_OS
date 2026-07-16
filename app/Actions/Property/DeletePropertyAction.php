<?php

namespace App\Actions\Property;

use App\Models\Property;

final class DeletePropertyAction
{
    public function __invoke(Property $property): void
    {
        $property->delete();
    }
}
