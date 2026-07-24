<?php

namespace App\Jobs;

use App\Actions\WorkOrder\DispatchWorkOrderAction;
use App\Models\WorkOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DispatchWorkOrderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly WorkOrder $workOrder)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(DispatchWorkOrderAction $action): void
    {
        $action($this->workOrder);
    }
}
