<?php
session_start();
include 'db.php';

// Seguridad
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    die("Acceso denegado");
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Comprobar si existe primero (opcional, pero buena práctica)
    $sql = "DELETE FROM pilotos WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: pilotos.php?msg=Piloto eliminado correctamente&type=success");
    } else {
        header("Location: pilotos.php?msg=Error al eliminar: " . mysqli_error($conn) . "&type=error");
    }
} else {
    header("Location: pilotos.php");
}
exit();
?>
