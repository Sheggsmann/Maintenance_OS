<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Property\CreatePropertyAction;
use App\Actions\Property\DeletePropertyAction;
use App\Actions\Property\ListPropertiesAction;
use App\Actions\Property\ShowPropertyAction;
use App\Actions\Property\UpdatePropertyAction;
use App\DTOs\Property\CreatePropertyData;
use App\DTOs\Property\PropertyFilters;
use App\DTOs\Property\UpdatePropertyData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Property\CreatePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Property::class, 'property');
    }

    public function index(Request $request, ListPropertiesAction $action)
    {
        $properties = $action(PropertyFilters::from($request));

        return ApiResponse::success(
            data: PropertyResource::collection($properties)
        );
    }

    public function show(Property $property, ShowPropertyAction $action)
    {
        return ApiResponse::success(
            data: new PropertyResource($action($property))
        );
    }

    public function store(CreatePropertyRequest $request, CreatePropertyAction $action)
    {
        $property = $action(CreatePropertyData::from($request));

        return ApiResponse::success(
            data: new PropertyResource($property->load('manager')),
            message: 'Property created successfully',
            status: 201,
        );
    }

    public function update(
        UpdatePropertyRequest $request,
        Property $property,
        UpdatePropertyAction $action
    ) {
        return ApiResponse::success(
            data: new PropertyResource(
                $action($property, UpdatePropertyData::from($request))
            ),
            message: 'Property updated successfully.'
        );
    }

    public function destroy(Property $property, DeletePropertyAction $action)
    {
        $this->authorize('delete', $property);

        $action($property);

        return ApiResponse::success(
            message: "Property deleted successfully"
        );
    }
}
