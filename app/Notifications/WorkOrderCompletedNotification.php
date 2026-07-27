<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkOrderCompletedNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject(
                'Work Order Completed'
            )
            ->greeting(
                'Hello!'
            )
            ->line(
                sprintf(
                    'Work Order %s has been completed.',
                    $this->workOrder->reference,
                )
            )
            ->line(
                sprintf(
                    'Property: %s',
                    $this->workOrder
                        ->maintenanceRequest
                        ->property
                        ->name
                )
            )
            ->line(
                sprintf(
                    'Unit: %s',
                    $this->workOrder
                        ->maintenanceRequest
                        ->unit
                        ->unit_number
                )
            )
            ->action(
                'View Work Order',
                url(
                    '/work-orders/' . $this->workOrder->id
                )
            );
    }
}
