<?php
include 'header.php';
include 'db.php';

// Seguridad: Solo admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: equipos.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_editing = $id > 0;

$nombre = '';
$marca = '';
$ano_fundacion = '';
$pais = '';
$descripcion = '';
$imagen_url = '';

if ($is_editing) {
    $sql = "SELECT * FROM equipos WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $nombre = $row['nombre'];
        $marca = $row['marca'];
        $ano_fundacion = $row['ano_fundacion'];
        $pais = $row['pais'];
        $descripcion = $row['descripcion'];
        $imagen_url = $row['imagen_url'];
    } else {
        echo "<script>window.location='equipos.php';</script>";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $marca = mysqli_real_escape_string($conn, $_POST['marca']);
    $ano_fundacion = !empty($_POST['ano_fundacion']) ? (int)$_POST['ano_fundacion'] : "NULL";
    $pais = mysqli_real_escape_string($conn, $_POST['pais']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $imagen_url = mysqli_real_escape_string($conn, $_POST['imagen_url']);

    if ($is_editing) {
        $update_sql = "UPDATE equipos SET nombre='$nombre', marca='$marca', ano_fundacion=$ano_fundacion, pais='$pais', descripcion='$descripcion', imagen_url='$imagen_url' WHERE id=$id";
        if (mysqli_query($conn, $update_sql)) {
            header("Location: equipos.php?msg=Equipo actualizado&type=success");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $insert_sql = "INSERT INTO equipos (nombre, marca, ano_fundacion, pais, descripcion, imagen_url) VALUES ('$nombre', '$marca', $ano_fundacion, '$pais', '$descripcion', '$imagen_url')";
        if (mysqli_query($conn, $insert_sql)) {
            header("Location: equipos.php?msg=Equipo creado&type=success");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container" style="max-width: 600px; margin-top: 20px;">
    <h2><?php echo $is_editing ? 'Editar Equipo' : 'Nuevo Equipo'; ?></h2>
    
    <?php if (isset($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="auth-container" style="max-width: 100%; margin: 0;">
        <div class="form-group">
            <label>Nombre del Equipo:</label>
            <input type="text" name="nombre" required value="<?php echo htmlspecialchars($nombre); ?>">
        </div>

        <div class="form-group">
            <label>Marca (Constructor):</label>
            <input type="text" name="marca" required value="<?php echo htmlspecialchars($marca); ?>">
        </div>

        <div class="form-group">
            <label>Año Fundación:</label>
            <input type="number" name="ano_fundacion" placeholder="Ej: 1950" value="<?php echo htmlspecialchars($ano_fundacion); ?>">
        </div>

        <div class="form-group">
            <label>País Base:</label>
            <input type="text" name="pais" required value="<?php echo htmlspecialchars($pais); ?>">
        </div>

        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="descripcion" rows="4" placeholder="Breve historia del equipo..."><?php echo htmlspecialchars($descripcion); ?></textarea>
        </div>

        <div class="form-group">
            <label>URL Logo:</label>
            <input type="text" name="imagen_url" placeholder="http://..." value="<?php echo htmlspecialchars($imagen_url); ?>">
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn-primary">Guardar</button>
            <a href="equipos.php" class="btn-primary" style="background: #555; text-align: center; text-decoration: none;">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
