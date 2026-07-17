<?php

namespace App\Http\Controllers\Api\V1;


use App\Actions\MaintenanceRequest\CreateMaintenanceRequestAction;
use App\DTOs\MaintenanceRequest\CreateMaintenanceRequestData;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceRequest\CreateMaintenanceRequestRequest;
use App\Http\Resources\MaintenanceRequestResource;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        CreateMaintenanceRequestRequest $request,
        CreateMaintenanceRequestAction $action,
    ) {
        $maintenanceRequest = $action(
            CreateMaintenanceRequestData::from($request)
        );

        return ApiResponse::success(
            data: new MaintenanceRequestResource(
                $maintenanceRequest->load([
                    'property',
                    'unit',
                    'tenant',
                    'contractor'
                ])
            ),
            message: 'Maintenance request created successfully.',
            status: 201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
