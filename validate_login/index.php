<?php
// validate_token.php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/connection.php';

// Verificar que se haya enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_data = json_decode(file_get_contents('php://input'), true);

    if (isset($post_data['user_id']) && isset($post_data['token'])) {
        $user_id = $post_data['user_id'];
        $token = $post_data['token'];

        $sql = "SELECT user_id FROM access_tokens WHERE user_id = ? AND token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Eliminar el registro del token
            $delete_sql = "DELETE FROM access_tokens WHERE user_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();
            $delete_stmt->close();

            // Consultar la tabla users para obtener nickname y transform
            $user_sql = "SELECT nickname, transform FROM users WHERE id = ?";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->bind_param("i", $user_id);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();

            if ($user_result->num_rows > 0) {
                $user_row = $user_result->fetch_assoc();
                $nickname = $user_row['nickname'];
                $transform = $user_row['transform'];

                $response = [
                    'error_code' => 0,
                    'user_id' => $user_id,
                    'nickname' => $nickname,
                    'transform' => $transform,
                ];

                 // Eliminar el registro del token
                $update_sql = "UPDATE `users` SET `last_login`=NOW() WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $user_id);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                $response = [
                    'error_code' => 14
                ];
            }

        } else {
            $response = [
                'error_code' => 15
            ];
        }
    } else {
        $response = [
            'error_code' => 4
        ];
    }
} else {
    $response = [
        'error_code' => 3
    ];
}

echo json_encode($response);
$conn->close();
?>
