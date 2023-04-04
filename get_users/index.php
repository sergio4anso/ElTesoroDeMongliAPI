<?php
// Incluye el archivo de conexión
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/connection.php';

// Verificar que se haya enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prepara la consulta SQL
    $sql = "SELECT id, nickname, last_login, transform FROM users WHERE active = 1";

    // Realiza la consulta
    $result = $conn->query($sql);

    // Verifica si se encontraron registros
    if ($result->num_rows > 0) {
        $users = [];

        // Almacena los registros en el array $users y convierte 'id' en entero
        while ($row = $result->fetch_assoc()) {
            $row['id'] = intval($row['id']); // Convierte 'id' en entero
            $users[] = $row;
        }

        // Prepara la respuesta en formato JSON
        $response = [
            'error_code' => 0,
            'content' => $users
        ];

        echo json_encode($response);
    } else {
        // Prepara la respuesta en formato JSON
        $response = [
            'error_code' => 0,
            'content' => []
        ];
        echo json_encode($response);
    }

    // Cierra la conexión
    $conn->close();
} else {
    $response = [
        'error_code' => 3,
        'message' => 'Método no permitido.',
    ];
    echo json_encode($response);
}
?>
