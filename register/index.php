<?php
header("Content-Type: application/json");
require_once $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/connection.php';

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_valid_password($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_-])(?=.*\d).{9,}$/', $password);
}

function is_valid_nickname($nickname) {
    return strlen($nickname) <= 20;
}

$error_code = 0;
$new_user_id = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    if ($json === false || $json === '') {
        $error_code = 4; // No se detecta un JSON en el envío
    } else {
        $data = json_decode($json, true);

        if (isset($data['mail']) && isset($data['password']) && isset($data['nickname'])) {
            $mail = $data['mail'];
            $password = $data['password'];
            $nickname = $data['nickname'];

            if (is_valid_email($mail)) {
                $sql = "SELECT * FROM users WHERE mail = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $mail);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $error_code = 1; // El usuario ya está registrado
                } else {
                    $sql = "SELECT * FROM users WHERE nickname = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $nickname);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $error_code = 9; // El nickname ya existe en la base de datos
                    } else {
                        if (is_valid_password($password) && is_valid_nickname($nickname)) {
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                            $sql = "INSERT INTO users (mail, password, nickname) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("sss", $mail, $hashed_password, $nickname);
                            $stmt->execute();
                            $new_user_id = $stmt->insert_id;
                            $error_code = 0; // Todo es válido
                        } else {
                            if (!is_valid_password($password)) {
                                $error_code = 8; // La contraseña no cumple con los requisitos
                            } else {
                                $error_code = 2; // El formato de mail no es válido
                            }
                        }
                    }
                }
                $stmt->close();
            } else {
                $error_code = 2; // El formato de mail no es válido
            }
        } else {
            if (!isset($data['mail'])) {
                $error_code = 7; // No se detecta el campo mail
            } elseif (!isset($data['nickname'])) {
                $error_code = 5; // No contiene nickname
            } elseif (!isset($data['password'])) {
                $error_code = 6; // No detecta password
            } else {
                $error_code = 4; // No se detecta un JSON en el envío
            }
        }
    }
} else {
    $error_code = 3; // La petición no es POST
}

if ($error_code === 0) {


    // Capturar la salida del script another_php_file.php
    ob_start();
    require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/createActivationToken/index.php';
    $response = ob_get_clean();


} 
echo json_encode(array("error_code" => $error_code));