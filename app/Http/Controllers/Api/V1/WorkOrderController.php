<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\WorkOrder\GenerateWorkOrderAction;
use App\Actions\WorkOrder\ListWorkOrdersAction;
use App\DTOs\WorkOrder\GenerateWorkOrderData;
use App\DTOs\WorkOrder\WorkOrderFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkOrder\GenerateWorkOrderRequest;
use App\Http\Requests\WorkOrder\ListWorkOrdersRequest;
use App\Http\Resources\WorkOrderCollection;
use App\Http\Resources\WorkOrderResource;
use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;
use App\Utils\ApiResponse;

class WorkOrderController extends Controller
{
    public function index(
        ListWorkOrdersRequest $request,
        ListWorkOrdersAction $action,
    ) {
        $this->authorize(
            'viewAny',
            WorkOrder::class,
        );

        $workOrders = $action->execute(
            WorkOrderFilters::from([
                'search' => $request->string('search')->toString(),

                'status' => $request->input('status'),

                'propertyId' => $request->integer('property_id') ?: null,

                'managerId' => $request->integer('manager_id') ?: null,

                'perPage' => $request->integer('per_page', 15),

            ])
        );

        return ApiResponse::success(
            data: new WorkOrderCollection(
                $workOrders
            ),
            message: 'Work Orders fetched successfully',
            status: 200
        );
    }


    public function generate(
        GenerateWorkOrderRequest $request,
        MaintenanceRequest $maintenanceRequest,
        GenerateWorkOrderAction $action
    ) {

        $this->authorize('generate', $maintenanceRequest);

        $workOrder = $action->execute(
            $maintenanceRequest,
            GenerateWorkOrderData::fromRequest($request),
            $request->user()
        );

        return ApiResponse::success(
            data: new WorkOrderResource($workOrder),
            status: 201,
            message: 'Work order generated successfully'
        );
    }
}
