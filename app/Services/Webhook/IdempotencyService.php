<?php

namespace App\Services\Webhook;

use App\Models\ProcessedWebhook;

class IdempotencyService
{
    public function alreadyProcessed(
        string $eventId
    ): bool {
        return ProcessedWebhook::where('event_id', $eventId)->exists();
    }

    public function record(
        array $payload
    ): ProcessedWebhook {

        return ProcessedWebhook::create([
            'event_id' => $payload['event_id'],

            'provider' => 'contractor',

            'reference' => $payload['reference'],

            'status' => $payload['status'],

            'payload_hash' => hash(
                'sha256',
                json_encode($payload)
            ),

            'processed_at' => now(),
        ]);
    }
}
