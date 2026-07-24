<?php

namespace App\Services\Webhook;

class HmacSignatureService
{
    public function generate(
        string $payload,
        string $timestamp
    ): string {
        return hash_hmac(
            'sha256',
            $timestamp . $payload,
            config('contractor.webhook_secret')
        );
    }

    public function verify(
        string $payload,
        string $timestamp,
        string $signature
    ): bool {
        if (abs(now()->timestamp - (int) $timestamp) > config('contractor.signature_tolerance')) {
            return false;
        }

        return hash_equals(
            $this->generate(
                $payload,
                $timestamp
            ),
            $signature
        );
    }
}
