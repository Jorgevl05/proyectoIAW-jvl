<?php
include 'header.php';
include 'db.php';

// Seguridad: Solo admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: pilotos.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_editing = $id > 0;

$nombre = '';
$dorsal = '';
$pais = '';
$id_equipo = '';
$puntos = 0;
$titulos = 0;
$ano_debut = '';
$biografia = '';
$imagen_url = '';

// Si es edición, cargar datos
if ($is_editing) {
    $sql = "SELECT * FROM pilotos WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $nombre = $row['nombre'];
        $dorsal = $row['dorsal'];
        $pais = $row['pais'];
        $id_equipo = $row['id_equipo'];
        $puntos = $row['puntos'];
        $titulos = $row['titulos'];
        $ano_debut = $row['ano_debut'];
        $biografia = $row['biografia'];
        $imagen_url = $row['imagen_url'];
    } else {
        echo "<script>window.location='pilotos.php';</script>";
        exit();
    }
}

// Procesar Formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $dorsal = (int)$_POST['dorsal'];
    $pais = mysqli_real_escape_string($conn, $_POST['pais']);
    $id_equipo = (int)$_POST['id_equipo'];
    $puntos = (int)$_POST['puntos'];
    $titulos = (int)$_POST['titulos'];
    $ano_debut = !empty($_POST['ano_debut']) ? (int)$_POST['ano_debut'] : "NULL";
    $biografia = mysqli_real_escape_string($conn, $_POST['biografia']);
    $imagen_url = mysqli_real_escape_string($conn, $_POST['imagen_url']);

    if ($is_editing) {
        $update_sql = "UPDATE pilotos SET nombre='$nombre', dorsal=$dorsal, pais='$pais', id_equipo=$id_equipo, puntos=$puntos, titulos=$titulos, ano_debut=$ano_debut, biografia='$biografia', imagen_url='$imagen_url' WHERE id=$id";
        if (mysqli_query($conn, $update_sql)) {
            header("Location: pilotos.php?msg=Piloto actualizado correctamente&type=success");
            exit();
        } else {
            $error = "Error al actualizar: " . mysqli_error($conn);
        }
    } else {
        // Validación dorsal único
        $check = mysqli_query($conn, "SELECT id FROM pilotos WHERE dorsal=$dorsal");
        if (mysqli_num_rows($check) > 0) {
            $error = "El número $dorsal ya está en uso.";
        } else {
            $insert_sql = "INSERT INTO pilotos (nombre, dorsal, pais, id_equipo, puntos, titulos, ano_debut, biografia, imagen_url) VALUES ('$nombre', $dorsal, '$pais', $id_equipo, $puntos, $titulos, $ano_debut, '$biografia', '$imagen_url')";
            if (mysqli_query($conn, $insert_sql)) {
                header("Location: pilotos.php?msg=Piloto creado correctamente&type=success");
                exit();
            } else {
                $error = "Error al crear: " . mysqli_error($conn);
            }
        }
    }
}

// Obtener equipos para el select
$equipos_sql = "SELECT id, nombre FROM equipos ORDER BY nombre ASC";
$equipos_result = mysqli_query($conn, $equipos_sql);
?>

<div class="container" style="max-width: 600px; margin-top: 20px;">
    <h2><?php echo $is_editing ? 'Editar Piloto' : 'Nuevo Piloto'; ?></h2>
    
    <?php if (isset($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="auth-container" style="max-width: 100%; margin: 0;">
        <div class="form-group">
            <label>Nombre Completo:</label>
            <input type="text" name="nombre" required value="<?php echo htmlspecialchars($nombre); ?>">
        </div>

        <div class="form-group">
            <label>Dorsal (#):</label>
            <input type="number" name="dorsal" required value="<?php echo htmlspecialchars($dorsal); ?>">
        </div>

        <div class="form-group">
            <label>Nacionalidad:</label>
            <input type="text" name="pais" required value="<?php echo htmlspecialchars($pais); ?>">
        </div>

        <div class="form-group">
            <label>Equipo:</label>
            <select name="id_equipo" required>
                <option value="">-- Seleccionar Equipo --</option>
                <?php while($team = mysqli_fetch_assoc($equipos_result)): ?>
                    <option value="<?php echo $team['id']; ?>" <?php if($id_equipo == $team['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($team['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Puntos (Clasificación):</label>
            <input type="number" name="puntos" value="<?php echo htmlspecialchars($puntos); ?>">
        </div>

        <div style="display: flex; gap: 15px;">
            <div class="form-group" style="flex: 1;">
                <label>Títulos Mundiales:</label>
                <input type="number" name="titulos" value="<?php echo htmlspecialchars($titulos); ?>">
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Año Debut:</label>
                <input type="number" name="ano_debut" placeholder="Ej: 2013" value="<?php echo htmlspecialchars($ano_debut); ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Biografía:</label>
            <textarea name="biografia" rows="5" placeholder="Historia del piloto..."><?php echo htmlspecialchars($biografia); ?></textarea>
        </div>

        <div class="form-group">
            <label>URL Imagen:</label>
            <input type="text" name="imagen_url" placeholder="http://..." value="<?php echo htmlspecialchars($imagen_url); ?>">
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn-primary">Guardar</button>
            <a href="pilotos.php" class="btn-primary" style="background: #555; text-align: center; text-decoration: none;">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
