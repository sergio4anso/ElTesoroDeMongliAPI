<?php

require_once __DIR__ . '/config_loader.php';

$config = mongli_config();
$dbConfig = $config['database'];

$conn = new mysqli(
    $dbConfig['host'],
    $dbConfig['user'],
    $dbConfig['password'],
    $dbConfig['name']
);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        'error_code' => 500,
        'message' => 'Database connection failed',
    ]));
}

if (!empty($dbConfig['charset'])) {
    $conn->set_charset($dbConfig['charset']);
}

?>
