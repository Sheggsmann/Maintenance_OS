<?php

namespace App\Services\Reports;

use App\Models\WorkOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CompletionReportService
{
    public function generate(WorkOrder $workOrder): string
    {
        $pdf = Pdf::loadView(
            'reports.completion',
            [
                'workOrder' => $workOrder->load([
                    'maintenanceRequest',
                    'maintenanceRequest.property',
                    'maintenanceRequest.unit',
                ]),
            ]
        );

        $path = sprintf('reports/completions/%s.pdf', $workOrder->reference);
        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }
}
