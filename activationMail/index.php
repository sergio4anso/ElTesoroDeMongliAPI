<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/config_loader.php';
require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/PHPMailer/src/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/ElTesoroDeMongliAPI/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendValidationEmail($recipientEmail, $nickname, $validationLink) {
    $config = mongli_config();
    $mailConfig = $config['mail'];

    if (empty($mailConfig['enabled'])) {
        return;
    }

    $phpMail = new PHPMailer(true);

    try {
        $phpMail->SMTPDebug = SMTP::DEBUG_OFF;
        $phpMail->isSMTP();
        $phpMail->Host = $mailConfig['host'];
        $phpMail->SMTPAuth = true;
        $phpMail->Username = $mailConfig['username'];
        $phpMail->Password = $mailConfig['password'];
        $phpMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpMail->Port = intval($mailConfig['port']);

        $phpMail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
        $phpMail->addAddress($recipientEmail, $nickname);

        $phpMail->isHTML(true);
        $phpMail->Subject = 'Valida tu cuenta de El tesoro de Mongli';
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

        $phpMail->send();
    } catch (Exception $e) {
        error_log("Activation email could not be sent: {$phpMail->ErrorInfo}");
    }
}

function obtenerUrlServidor() {
    $config = mongli_config();

    if (!empty($config['app']['base_url'])) {
        return rtrim($config['app']['base_url'], '/');
    }

    $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
    $nombreServidor = $_SERVER['SERVER_NAME'];
    $puerto = $_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443' ? '' : ':' . $_SERVER['SERVER_PORT'];

    return $protocolo . $nombreServidor . $puerto;
}

$urlServidor = obtenerUrlServidor();
$validationLink = $urlServidor . '/ElTesoroDeMongliAPI/validation?user_id=' . $new_user_id . '&token=' . $token;
sendValidationEmail($mail, $nickname, $validationLink);
?>
