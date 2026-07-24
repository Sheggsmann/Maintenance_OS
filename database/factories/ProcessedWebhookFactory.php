<?php

namespace Database\Factories;

use App\Models\ProcessedWebhook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProcessedWebhook>
 */
class ProcessedWebhookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => (string) Str::uuid(),
            'provider' => 'contractor',
            'reference' => 'WO-00001',
            'status' => 'completed',
            'payload_hash' => Str::random(64),
            'processed_at' => now(),
        ];
    }
}
