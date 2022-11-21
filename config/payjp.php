<?php

return [
    'public_key' => env('PAYJP_PUBLIC_KEY', ''),
    'secret_key' => env('PAYJP_SECRET_KEY', ''),
    'webhook' => env('PAYJP_WEBHOOK_SIGNATURE', ''),
];
