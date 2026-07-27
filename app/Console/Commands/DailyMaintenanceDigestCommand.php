<?php

namespace App\Console\Commands;

use App\Services\Maintenance\DailyDigestService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('maintenance:daily-digest')]
#[Description('Send daily maintenance summary to property managers')]
class DailyMaintenanceDigestCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(DailyDigestService $service)
    {
        $count = $service->execute();

        $this->info("Daily digest sent to {$count} manager(s).");

        return self::SUCCESS;
    }
}
