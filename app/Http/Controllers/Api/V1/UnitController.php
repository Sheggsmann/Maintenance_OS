<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Unit\CreateUnitAction;
use App\Actions\Unit\ListUnitsAction;
use App\Actions\Unit\ShowUnitAction;
use App\DTOs\Units\CreateUnitData;
use App\DTOs\Units\UnitFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\CreateUnitRequest;
use App\Http\Requests\Unit\ListUnitsRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{

    public function index(ListUnitsRequest $request, ListUnitsAction $action)
    {
        $units = $action(UnitFilters::from($request));
        return ApiResponse::success(data: UnitResource::collection($units));
    }

    public function store(CreateUnitRequest $request, CreateUnitAction $action)
    {
        $unit = $action(CreateUnitData::from($request));

        return ApiResponse::success(
            data: new UnitResource(
                $unit->load(['property', 'tenant'])
            ),
            message: "Unit created successfully",
            status: 201
        );
    }

    public function show(Unit $unit, ShowUnitAction $action)
    {
        $this->authorize('view', $unit);

        return ApiResponse::success(
            data: new UnitResource($action($unit))
        );
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
