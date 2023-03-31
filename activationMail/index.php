<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/PHPMailer/src/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function sendValidationEmail($recipientEmail, $nickname, $validationLink) {
    $phpMail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $phpMail->SMTPDebug = SMTP::DEBUG_OFF;
        $phpMail->isSMTP();
        $phpMail->Host = 'smtpout.secureserver.net';
        $phpMail->SMTPAuth = true;
        $phpMail->Username = 'connect@jantechnology.es';
        $phpMail->Password = 'Mongli2023';
        $phpMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        // $mail->SMTPSecure = "tls";
        $phpMail->Port = 587;

        // Remitente y destinatario
        $phpMail->setFrom('connect@jantechnology.es', 'El tesoro de Mongli');
        $phpMail->addAddress($recipientEmail, $nickname);

        // Contenido del correo
        $phpMail->isHTML(true);
        $phpMail->Subject = 'Valid tu cuenta de El tesoro de Mongli';
        $phpMail->Body = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    .button {
                        background-color: #4CAF50;
                        border: none;
                        color: white;
                        padding: 10px 20px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;
                        margin: 10px 2px;
                        cursor: pointer;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>
                <h1>¡Hola, ' . htmlspecialchars($nickname) . '!</h1>
                <p>Bienvenido al increíble mundo de Mongli.</p>
                <p>Por favor, haz clic en el botón a continuación para validar tu cuenta:</p>
                <a href="' . $validationLink . '" class="button">Validar cuenta</a>
            </body>
            </html>';

        // Enviar correo
        $phpMail->send();
        // echo 'Mensaje enviado correctamente';
    } catch (Exception $e) {
        // $error_code = 11;
        echo "No se pudo enviar el mensaje. Error: {$phpMail->ErrorInfo}";
    }
}

function obtenerUrlServidor() {
    $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
    $nombreServidor = $_SERVER['SERVER_NAME'];
    $puerto = $_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443' ? '' : ':' . $_SERVER['SERVER_PORT'];
    $ruta = $protocolo . $nombreServidor . $puerto;
    
    return $ruta;
}

$urlServidor = obtenerUrlServidor();

// Ejemplo de uso
$validationLink = $urlServidor . '/ElTesoroDeMongliAPI/validation?user_id=' . $new_user_id . '&token=' . $token;
sendValidationEmail($mail, $nickname, $validationLink);
?>
