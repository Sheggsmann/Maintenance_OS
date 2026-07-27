<?php

namespace App\Jobs;

use App\Models\WorkOrder;
use App\Services\Reports\CompletionReportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateCompletionReportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly WorkOrder $workOrder) {}

    /**
     * Execute the job.
     */
    public function handle(CompletionReportService $service): void
    {
        $path = $service->generate($this->workOrder);
        $this->workOrder->update([
            'completion_report_path' => $path,
        ]);
    }
}
