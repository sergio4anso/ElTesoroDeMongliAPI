<?php
// Importar el archivo de conexión
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/connection.php';

// Verificar que se haya enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');

    $data = base64_decode($input);
    $data = json_decode($data, true);
    if (isset($data['usersUpdateData']))
    {
        $usersUpdateData = $data['usersUpdateData'];

        // Verificar si se recibió un array de objetos usuario
        if (is_array($usersUpdateData)) {
            // Preparar la consulta SQL para actualizar la columna "transform"
            $sql = "UPDATE users SET transform = ? WHERE id = ?";

            // Crear un statement preparado
            if ($stmt = $conn->prepare($sql)) {
            // Inicializar una variable para verificar el formato de los objetos usuario
            $invalid_format = false;
            $update_error = false;
            // Recorrer el array de objetos usuario
            foreach ($usersUpdateData as $user) {
                // Verificar si el objeto tiene las keys "id" y "transform", y si sus tipos son correctos
                if (isset($user['id']) && is_int($user['id']) && isset($user['transform']) && is_string($user['transform'])) {
                    // Extraer la información del objeto usuario
                    $id = $user['id'];
                    $transform = $user['transform'];


                    // Vincular los parámetros a la consulta SQL
                    $stmt->bind_param("si", $transform, $id);

                    // Ejecutar la consulta SQL y verificar si ocurrió algún error
                    if (!$stmt->execute()) {
                        $update_error = true;
                        break;
                    }
                } else {
                    // Marcar como formato inválido y salir del bucle
                    $invalid_format = true;
                    break;
                }
            }

            // Cerrar el statement
            $stmt->close();

            if ($invalid_format) {
                // Enviar una respuesta de error en formato JSON
                echo json_encode(["error_code" => 17]);
            } elseif ($update_error) {
                // Enviar una respuesta de error en formato JSON
                echo json_encode(["error_code" => 10]);
            }  else {
                // Enviar una respuesta de éxito en formato JSON
                echo json_encode(["error_code" => 0]);
            }
                


            } else {
                // Enviar una respuesta de error en formato JSON
                echo json_encode(["error_code" => 10]);
            }
        } else {
            // Enviar una respuesta de error en formato JSON
            echo json_encode(["error_code" => 16]);
        }
    }
    else
    {
        echo json_encode(["error_code" => 4]);
    }
} else {
    // Enviar una respuesta de error en formato JSON
    echo json_encode(["error_code" => 3]);
}
// Cerrar la conexión a la base de datos
$conn->close();
?>