<?php

function mongli_array_merge_recursive_distinct(array $base, array $override): array
{
    foreach ($override as $key => $value) {
        if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
            $base[$key] = mongli_array_merge_recursive_distinct($base[$key], $value);
        } else {
            $base[$key] = $value;
        }
    }

    return $base;
}

function mongli_env_bool(string $name, bool $default = false): bool
{
    $value = getenv($name);

    if ($value === false || $value === '') {
        return $default;
    }

    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

function mongli_config(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $config = [
        'database' => [
            'host' => getenv('MONGLI_DB_HOST') ?: 'localhost',
            'user' => getenv('MONGLI_DB_USER') ?: '',
            'password' => getenv('MONGLI_DB_PASSWORD') ?: '',
            'name' => getenv('MONGLI_DB_NAME') ?: 'eltesorodemongli',
            'charset' => getenv('MONGLI_DB_CHARSET') ?: 'utf8mb4',
        ],
        'mail' => [
            'enabled' => mongli_env_bool('MONGLI_MAIL_ENABLED', false),
            'host' => getenv('MONGLI_MAIL_HOST') ?: '',
            'username' => getenv('MONGLI_MAIL_USERNAME') ?: '',
            'password' => getenv('MONGLI_MAIL_PASSWORD') ?: '',
            'port' => intval(getenv('MONGLI_MAIL_PORT') ?: 587),
            'from_email' => getenv('MONGLI_MAIL_FROM_EMAIL') ?: '',
            'from_name' => getenv('MONGLI_MAIL_FROM_NAME') ?: 'El tesoro de Mongli',
        ],
        'app' => [
            'base_url' => getenv('MONGLI_APP_BASE_URL') ?: '',
        ],
    ];

    $localConfigPath = __DIR__ . '/config.local.php';

    if (file_exists($localConfigPath)) {
        $localConfig = require $localConfigPath;

        if (is_array($localConfig)) {
            $config = mongli_array_merge_recursive_distinct($config, $localConfig);
        }
    }

    return $config;
}

?>
