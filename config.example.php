<?php

return [
    'database' => [
        'host' => 'localhost',
        'user' => 'mongli_dev',
        'password' => 'CHANGE_ME_LOCAL_PASSWORD',
        'name' => 'eltesorodemongli',
        'charset' => 'utf8mb4',
    ],
    'mail' => [
        // Keep false for local development unless SMTP is configured.
        'enabled' => false,
        'host' => 'smtp.example.com',
        'username' => 'user@example.com',
        'password' => 'CHANGE_ME_SMTP_PASSWORD',
        'port' => 587,
        'from_email' => 'noreply@example.com',
        'from_name' => 'El tesoro de Mongli',
    ],
    'app' => [
        // Leave empty to auto-detect from the current request.
        'base_url' => '',
    ],
];

?>
