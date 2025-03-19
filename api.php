<?php

$config = require __DIR__ . '/config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Security check for your custom proxy API key
if (!isset($_GET['key']) || $_GET['key'] !== $config['PROXY_KEY']) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Ensure cache directory exists
if (!is_dir($config['CACHE_DIR'])) {
    mkdir($config['CACHE_DIR'], 0777, true);
}

// Helper functions
function getCacheKey($params) {
    ksort($params);
    return md5(json_encode($params));
}

function checkRateLimit($config) {
    $currentTime = time();
    $windowStart = $currentTime - 60;

    $data = file_exists($config['RATE_LIMIT_FILE']) ? json_decode(file_get_contents($config['RATE_LIMIT_FILE']), true) : [];
    $data = array_filter($data, fn($timestamp) => $timestamp > $windowStart);

    if (count($data) >= $config['RATE_LIMIT']) {
        http_response_code(429);
        echo json_encode(['error' => 'Rate limit exceeded']);
        exit;
    }

    $data[] = $currentTime;
    file_put_contents($config['RATE_LIMIT_FILE'], json_encode($data));
}

// Collect parameters
$params = [
    'lat' => $_GET['lat'] ?? null,
    'lng' => $_GET['lng'] ?? null,
    'rad' => $_GET['rad'] ?? null,
    'type' => $_GET['type'] ?? null,
    'sort' => $_GET['sort'] ?? null,
    'apikey' => $config['API_KEY']
];

// Validate required params
if (!$params['lat'] || !$params['lng'] || !$params['rad'] || !$params['type']) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

// Caching logic
$cacheKey = getCacheKey($params);
$cacheFile = $config['CACHE_DIR'] . "/{$cacheKey}.json";

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $config['CACHE_TTL'])) {
    echo file_get_contents($cacheFile);
    exit;
}

// Rate limiting
checkRateLimit($config);

// Forward request to Tankerkönig API
$query = http_build_query($params);
$apiUrl = "{$config['BASE_URL']}?$query";
$response = file_get_contents($apiUrl);

if ($response) {
    file_put_contents($cacheFile, $response);
    echo $response;
} else {
    http_response_code(502);
    echo json_encode(['error' => 'Failed to fetch data from external API']);
}
