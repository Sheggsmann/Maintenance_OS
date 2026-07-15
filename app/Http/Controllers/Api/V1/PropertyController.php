<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Property\CreateProperty;
use App\DTOs\Property\CreatePropertyData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Property\CreatePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Utils\ApiResponse;

class PropertyController extends Controller
{
    public function store(CreatePropertyRequest $request, CreateProperty $action)
    {
        $property = $action(CreatePropertyData::from($request));

        return ApiResponse::success(
            data: new PropertyResource($property->load('manager')),
            message: 'Property created successfully',
            status: 201,
        );
    }
}
