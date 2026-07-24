<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\MaintenanceRequest\ApproveMaintenanceRequestAction;
use App\Actions\MaintenanceRequest\AssignContractorAction;
use App\Actions\MaintenanceRequest\CancelMaintenanceRequestAction;
use App\Actions\MaintenanceRequest\CompleteMaintenanceRequestAction;
use App\Actions\MaintenanceRequest\CreateMaintenanceRequestAction;
use App\Actions\MaintenanceRequest\ListMaintenanceRequestsAction;
use App\Actions\MaintenanceRequest\ShowMaintenanceRequestAction;
use App\Actions\MaintenanceRequest\StartMaintenanceRequestAction;
use App\DTOs\MaintenanceRequest\ApproveMaintenanceRequestData;
use App\DTOs\MaintenanceRequest\AssignContractorData;
use App\DTOs\MaintenanceRequest\CancelMaintenanceRequestData;
use App\DTOs\MaintenanceRequest\CreateMaintenanceRequestData;
use App\DTOs\MaintenanceRequest\MaintenanceRequestFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceRequest\ApproveMaintenanceRequestRequest;
use App\Http\Requests\MaintenanceRequest\AssignContractorRequest;
use App\Http\Requests\MaintenanceRequest\CancelMaintenanceRequestRequest;
use App\Http\Requests\MaintenanceRequest\CompleteMaintenanceRequestRequest;
use App\Http\Requests\MaintenanceRequest\CreateMaintenanceRequestRequest;
use App\Http\Requests\MaintenanceRequest\ListMaintenanceRequestRequest;
use App\Http\Requests\MaintenanceRequest\StartMaintenanceRequestRequest;
use App\Http\Resources\MaintenanceRequestResource;
use App\Http\Resources\WorkOrderResource;
use App\Models\MaintenanceRequest;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    public function index(
        ListMaintenanceRequestRequest $request,
        ListMaintenanceRequestsAction $action
    ) {
        $requests = $action(
            MaintenanceRequestFilters::from($request),
            $request->user(),
        );

        return ApiResponse::success(
            data: MaintenanceRequestResource::collection($requests)
        );
    }

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

    public function show(MaintenanceRequest $maintenanceRequest, ShowMaintenanceRequestAction $action)
    {
        $this->authorize('view', $maintenanceRequest);
        return ApiResponse::success(
            data: new MaintenanceRequestResource($action($maintenanceRequest)),
        );
    }

    public function approve(
        ApproveMaintenanceRequestRequest $request,
        MaintenanceRequest $maintenanceRequest,
        ApproveMaintenanceRequestAction $action
    ) {
        $workOrder = $action(new ApproveMaintenanceRequestData(
            maintenanceRequest: $maintenanceRequest,
            approvedBy: $request->user(),
            scopeOfWork: $request->validated('scope_of_work'),
            estimatedCost: (float) $request->validated('estimated_cost'),
            estimatedHours: (float) $request->validated('estimated_hours'),
            scheduledFor: $request->date('scheduled_for')?->toMutable(),
        ));

        return ApiResponse::success(
            data: new WorkOrderResource($workOrder),
            message: '',
            status: 200
        );
    }

    public function assign(
        AssignContractorRequest $request,
        MaintenanceRequest $maintenanceRequest,
        AssignContractorAction $action
    ) {
        $this->authorize('assign', $maintenanceRequest);

        $maintenanceRequest = $action($maintenanceRequest, AssignContractorData::from($request));

        return ApiResponse::success(
            data: new MaintenanceRequestResource($maintenanceRequest),
            message: 'Contractor assigned successfully.'
        );
    }

    public function start(
        StartMaintenanceRequestRequest $request,
        MaintenanceRequest $maintenanceRequest,
        StartMaintenanceRequestAction $action
    ) {
        return ApiResponse::success(
            data: new MaintenanceRequestResource($action($maintenanceRequest)),
            message: "Maintenance work started successfully"
        );
    }

    public function complete(
        CompleteMaintenanceRequestRequest $request,
        MaintenanceRequest $maintenanceRequest,
        CompleteMaintenanceRequestAction $action,
    ) {
        return ApiResponse::success(
            data: new MaintenanceRequestResource(
                $action($maintenanceRequest)
            ),
            message: 'Maintenance request completed successfully.',
        );
    }

    public function cancel(
        CancelMaintenanceRequestRequest $request,
        MaintenanceRequest $maintenanceRequest,
        CancelMaintenanceRequestAction $action,
    ) {
        return ApiResponse::success(
            data: new MaintenanceRequestResource(
                $action($maintenanceRequest, CancelMaintenanceRequestData::from($request))
            ),
            message: 'Maintenance request cancelled successfully.',
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
