<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\WorkOrder\GenerateWorkOrderAction;
use App\DTOs\WorkOrder\GenerateWorkOrderData;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkOrder\GenerateWorkOrderRequest;
use App\Http\Resources\WorkOrderResource;
use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;
use App\Utils\ApiResponse;

class WorkOrderController extends Controller
{
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
