<?php

use App\Enums\WorkOrderStatusEnum;
use App\Jobs\GenerateCompletionReportJob;
use App\Models\WorkOrder;
use App\Services\Reports\CompletionReportService;
use Illuminate\Support\Facades\Storage;

it('generates completion report', function () {
    Storage::fake('local');

    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatusEnum::COMPLETED,
    ]);

    $job = new GenerateCompletionReportJob($workOrder);

    $job->handle(app(CompletionReportService::class));

    Storage::disk('local')->assertExists(
        "reports/completions/{$workOrder->reference}.pdf"
    );
});

it('handles missing work order gracefully', function () {
    Storage::fake('public');

    $workOrder = WorkOrder::factory()->create();

    $id = $workOrder->id;

    $workOrder->delete();

    $job = new GenerateCompletionReportJob($workOrder);

    expect(
        fn() =>
        $job->handle(app(CompletionReportService::class))
    )->not->toThrow(Exception::class);
});


it('stores report path on work order', function () {
    Storage::fake('public');

    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatusEnum::COMPLETED,
    ]);

    (new GenerateCompletionReportJob($workOrder))
        ->handle(app(CompletionReportService::class));

    expect(
        $workOrder->fresh()->completion_report_path
    )->not->toBeNull();
});
