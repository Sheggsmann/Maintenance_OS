<?php

namespace App\Jobs\Contractor;

use App\Actions\Webhook\ProcessContractorWebhookAction;
use App\DTOs\Webhook\ContractorWebhookData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessContractorWebhookJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public ContractorWebhookData $data) {}

    /**
     * Execute the job.
     */
    public function handle(ProcessContractorWebhookAction $action): void
    {
        $action($this->data);
    }
}
