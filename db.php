<?php
$servername = "localhost";
$username = "root";
$password = ""; // Por defecto en XAMPP suele ser vacío
$dbname = "motogp_db";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (!$conn) {
    // En producción no se debe mostrar el error específico al usuario, pero para desarrollo ayuda
    die("Connection failed: " . mysqli_connect_error());
}

// Establecer charset a utf8
mysqli_set_charset($conn, "utf8mb4");
?>
