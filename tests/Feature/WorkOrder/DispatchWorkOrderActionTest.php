<?php

use App\Actions\WorkOrder\DispatchWorkOrderAction;
use App\Enums\DispatchStatusEnum;
use App\Enums\WorkOrderStatusEnum;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Http;

it('ensures dispatch sends payload to contractor api', function () {
    Http::fake([
        '*' => Http::response(['success' => true], 200),
    ]);

    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatusEnum::APPROVED->value,
    ]);

    app(DispatchWorkOrderAction::class)($workOrder);

    Http::assertSent(function ($request) use ($workOrder) {
        return $request->url() === config('contractor.base_url') . '/work-orders'
            && $request['reference'] === $workOrder->reference;
    });
});

it('creates contractor dispatch record after successful dispatch', function () {
    Http::fake([
        '*' => Http::response([
            'dispatch_id' => 'ext_123',
            'success' => true,
            'status' => DispatchStatusEnum::SENT->value,
            'external_reference' => 'EXT-123',
        ], 200),
    ]);

    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatusEnum::APPROVED->value
    ]);

    app(DispatchWorkOrderAction::class)($workOrder);

    $this->assertDatabaseHas('contractor_dispatches', [
        'work_order_id' => $workOrder->id,
        'status' => DispatchStatusEnum::SENT->value,
    ]);
});

it('updates work order status after successful dispatch', function () {
    Http::fake([
        '*' => Http::response([], 200),
    ]);

    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatusEnum::APPROVED->value,
    ]);

    app(DispatchWorkOrderAction::class)($workOrder);

    expect($workOrder->fresh()->status)
        ->toBe(WorkOrderStatusEnum::DISPATCHED->value);
});

it('stores dispatch error when contractor api fails', function () {
    Http::fake([
        '*' => Http::response([
            'message' => 'Server Error',
        ], 500),
    ]);

    $workOrder = WorkOrder::factory()->create();

    app(DispatchWorkOrderAction::class)($workOrder);

    $this->assertDatabaseHas('contractor_dispatches', [
        'work_order_id' => $workOrder->id,
        'status' => DispatchStatusEnum::FAILED->value,
    ]);
});
