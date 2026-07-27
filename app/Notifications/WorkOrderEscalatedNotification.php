<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkOrderEscalatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly WorkOrder $workOrder,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $maintenanceRequest = $this->workOrder
            ->maintenanceRequest()
            ->with(['property', 'unit'])
            ->first();

        return (new MailMessage)
            ->subject('High Priority Work Order Escalated')
            ->greeting("Hello {$notifiable->name},")
            ->line('A high-priority work order has not been accepted by the contractor within four hours.')
            ->line("Reference: {$this->workOrder->reference}")
            ->line("Property: {$maintenanceRequest->property->name}")
            ->line("Unit: {$maintenanceRequest->unit->unit_number}")
            ->line("Issue: {$maintenanceRequest->title}")
            ->line("Current Status: {$this->workOrder->status->value}")
            ->action(
                'Review Work Order',
                url("/work-orders/{$this->workOrder->id}")
            )
            ->line('Please review the work order and determine whether contractor reassignment or manual intervention is required.');
    }
}
