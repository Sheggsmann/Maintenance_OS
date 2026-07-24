<?php

return [
    'base_url' => env('CONTRACTOR_API_URL'),

    'api_key' => env('CONTRACTOR_API_KEY'),

    'webhook_secret' => env('CONTRACTOR_WEBHOOK_SECRET'),

    'timeout' => env('CONTRACTOR_TIMEOUT', 15),

    'retry_times' => env('CONTRACTOR_RETRY_TIMES', 3),

    'signature_tolerance' => env(
        'CONTRACTOR_SIGNATURE_TOLERANCE',
        300
    ),
];
