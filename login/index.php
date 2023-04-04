<?php
// login.php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/connection.php';

// Verificar que se haya enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_data = json_decode(file_get_contents('php://input'), true);

    if (isset($post_data['mail']) && isset($post_data['password'])) {
        $mail = $post_data['mail'];
        $password = $post_data['password'];

        $sql = "SELECT id, password FROM users WHERE (nickname = ? OR mail = ?) AND active = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $mail, $mail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            if (password_verify($password, $hashed_password)) {
                $new_user_id = $row['id'];
                // se genera el token
                ob_start();
                require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/createActivationToken/index.php';
                $response = ob_get_clean();
              
                $response = [
                    'error_code' => 0,
                    'content' => [
                        'user_id' => $new_user_id,
                        'token' => $token,
                    ],
                ];
            } else {
                $response = [
                    'error_code' => 12
                ];
            }
        } else {
            $response = [
                'error_code' => 14
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
// Cerrar la sentencia y la conexión
$stmt->close();
$conn->close();
?>