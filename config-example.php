<?php
return [
    // External Tankerkönig API key
    'API_KEY' => '00000000-0000-0000-0000-000000000002',

    // Your custom proxy API key
    'PROXY_KEY' => 'your-proxy-key',

    // External API base URL
    'BASE_URL' => 'https://creativecommons.tankerkoenig.de/json/list.php',

    // Cache directory and settings
    'CACHE_DIR' => __DIR__ . '/cache',
    'CACHE_TTL' => 300, // in seconds

    // Rate limiting settings
    'RATE_LIMIT' => 30, // max requests per minute
    'RATE_LIMIT_FILE' => __DIR__ . '/rate_limit.json'
];
