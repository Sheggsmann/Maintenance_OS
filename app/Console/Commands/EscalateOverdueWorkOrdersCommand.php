<?php

namespace App\Console\Commands;

use App\Services\Maintenance\EscalationService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('maintenance:escalate-overdue')]
#[Description('Escalate overdue dispatched work orders.')]
class EscalateOverdueWorkOrdersCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(EscalationService $service)
    {
        $count = $service->execute();
        $this->info(sprintf('Escalated %d work orders.', $count));
        return self::SUCCESS;
    }
}
