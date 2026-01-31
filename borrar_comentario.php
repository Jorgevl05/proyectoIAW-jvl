<?php
session_start();
include 'db.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $id_comentario = (int)$_GET['id'];
    $id_piloto = isset($_GET['piloto']) ? (int)$_GET['piloto'] : 0;
    $user_id = $_SESSION['user_id'];
    $user_rol = $_SESSION['user_rol'];

    // Verificar si el usuario es dueño del comentario o admin
    $check_sql = "SELECT id_usuario FROM comentarios WHERE id = $id_comentario";
    $result = mysqli_query($conn, $check_sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['id_usuario'] == $user_id || $user_rol == 'admin') {
            $delete_sql = "DELETE FROM comentarios WHERE id = $id_comentario";
            mysqli_query($conn, $delete_sql);
        }
    }
    
    header("Location: ver_piloto.php?id=$id_piloto");
} else {
    header("Location: index.html");
}
exit();
?>
