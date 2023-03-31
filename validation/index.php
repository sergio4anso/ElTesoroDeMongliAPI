<?php
// Obtener variables GET
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$token = isset($_GET['token']) ? $_GET['token'] : null;

// Verificar si las variables user_id y token están presentes
if ($user_id !== null && $token !== null) {
    // Incluir archivo de conexión
    require_once $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/connection.php';

    // Preparar la consulta para verificar si el user_id y el token existen en la tabla access_token
    $stmt = $conn->prepare("SELECT * FROM access_tokens WHERE user_id = ? AND token = ?");
    $stmt->bind_param("is", $user_id, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si el user_id y el token existen
    if ($result->num_rows > 0) {
        // Eliminar el registro de la tabla access_token
        $stmt_delete = $conn->prepare("DELETE FROM access_tokens WHERE user_id = ? AND token = ?");
        $stmt_delete->bind_param("is", $user_id, $token);
        $stmt_delete->execute();

        // Actualizar la tabla users
        $stmt_update = $conn->prepare("UPDATE users SET active = 1 WHERE id = ?");
        $stmt_update->bind_param("i", $user_id);
        $stmt_update->execute();

        $login_url = $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/login/';
        // Mostrar mensaje de éxito en HTML
        echo "<h1>Su correo ha sido validado</h1>";
        echo "<p><a href='" . $login_url . "'>Loguéate para comenzar a jugar</a></p>";
    } else {
        // Mostrar mensaje de error en HTML
        echo "<h1>Su correo no ha podido ser validado</h1>";
        echo "<p>Por favor, póngase en contacto con los desarrolladores.</p>";
    }
} else {
    // Mostrar mensaje de error en HTML
    echo "<h1>Error</h1>";
    echo "<p>Parámetros no válidos</p>";
    echo "<p>Por favor, póngase en contacto con los desarrolladores.</p>";
}
?>