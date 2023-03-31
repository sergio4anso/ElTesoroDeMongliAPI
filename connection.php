<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "mongli_fck";
$password = "bTT*fci7YRQpmXsh";
$dbname = "eltesorodemongli";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>
