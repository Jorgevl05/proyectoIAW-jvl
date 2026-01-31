<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $id_usuario = $_SESSION['user_id'];
    $id_piloto = (int)$_POST['id_piloto'];
    $comentario = mysqli_real_escape_string($conn, $_POST['comentario']);
    $valoracion = (int)$_POST['valoracion'];

    if (!empty($comentario)) {
        $sql = "INSERT INTO comentarios (id_usuario, id_piloto, comentario, valoracion) VALUES ($id_usuario, $id_piloto, '$comentario', $valoracion)";
        if (mysqli_query($conn, $sql)) {
            header("Location: ver_piloto.php?id=$id_piloto&msg=Comentario publicado");
        } else {
            header("Location: ver_piloto.php?id=$id_piloto&msg=Error al publicar");
        }
    } else {
        header("Location: ver_piloto.php?id=$id_piloto&msg=El comentario no puede estar vacío");
    }
} else {
    header("Location: index.html");
}
exit();
?>
