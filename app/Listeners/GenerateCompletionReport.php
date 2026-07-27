<?php

namespace App\Listeners;

use App\Events\WorkOrderCompleted;
use App\Jobs\GenerateCompletionReportJob;


class GenerateCompletionReport
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(WorkOrderCompleted $event): void
    {
        GenerateCompletionReportJob::dispatch($event->workOrder);
    }
}
