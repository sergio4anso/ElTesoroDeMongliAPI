<?php
header("Content-Type: application/json");
require_once 'connection.php';

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_valid_password($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{9,}$/', $password);
}

function is_valid_nickname($nickname) {
    return strlen($nickname) <= 20;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $mail = $data['mail'];
    $password = $data['password'];
    $nickname = $data['nickname'];
    $error_code = 0;

    if (is_valid_email($mail)) {
        $sql = "SELECT * FROM users WHERE mail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_code = 1; // El usuario ya está registrado
        } else {
            if (is_valid_password($password) && is_valid_nickname($nickname)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO users (mail, password, nickname) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $mail, $hashed_password, $nickname);
                $stmt->execute();
                
                $error_code = 0; // Todo es válido
            } else {
                $error_code = 2; // El formato de mail no es válido
            }
        }
        $stmt->close();
    } else {
        $error_code = 2; // El formato de mail no es válido
    }
    echo json_encode(array("error_code" => $error_code));
}
