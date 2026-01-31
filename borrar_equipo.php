<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    die("Acceso denegado");
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM equipos WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: equipos.php?msg=Equipo eliminado&type=success");
    } else {
        header("Location: equipos.php?msg=Error al eliminar: " . mysqli_error($conn) . "&type=error");
    }
} else {
    header("Location: equipos.php");
}
exit();
?>
