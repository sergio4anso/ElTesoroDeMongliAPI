<?php

// Crear un token
$token = bin2hex(random_bytes(32));

// Preparar la consulta SQL para insertar o reemplazar una fila en la tabla "access_tokens"
$sql = "INSERT INTO access_tokens (user_id, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token = ?";

// Preparar la sentencia
$stmt = $conn->prepare($sql);

// Vincular los parámetros a la sentencia
$stmt->bind_param('iss', $new_user_id, $token, $token);

// Ejecutar la sentencia
$result = $stmt->execute();

// Verificar el resultado
if (!$result) {
    $error_code = 10; // Error al insertar o reemplazar el token en la base de datos
}



if ($error_code === 0) {

    if (isset($create_mail))
    {
        // Capturar la salida del script another_php_file.php
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/activationMail/index.php';
        $response = ob_get_clean();
    }

} 