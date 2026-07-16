<?php

namespace App\Actions\Unit;

use App\Models\Unit;

final class ShowUnitAction
{
    public function __invoke(Unit $unit): Unit
    {
        return $unit->load([
            'property',
            'tenant',
        ]);
    }
}
