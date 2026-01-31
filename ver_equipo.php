<?php
include 'header.php';
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: equipos.php");
    exit();
}

$id_equipo = (int)$_GET['id'];

// Obtener info del equipo
$sql = "SELECT * FROM equipos WHERE id = $id_equipo";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='container'><p>Equipo no encontrado</p></div>";
    include 'footer.php';
    exit();
}

$equipo = mysqli_fetch_assoc($result);

// Obtener pilotos del equipo
$sql_pilotos = "SELECT * FROM pilotos WHERE id_equipo = $id_equipo";
$res_pilotos = mysqli_query($conn, $sql_pilotos);
?>

<div class="container" style="margin-top: 30px;">
    <!-- Detalles del Equipo -->
    <div style="display: flex; gap: 40px; background: #2c2c2c; padding: 30px; border-radius: 10px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px; text-align: center;">
            <?php 
                $img = $equipo['imagen_url'];
                if (!$img) {
                    $img = 'https://via.placeholder.com/300x200?text=Logo+Equipo';
                }
            ?>
            <img src="<?php echo $img; ?>" style="width: 100%; max-width: 400px; background:white; padding: 20px; border-radius: 10px;">
        </div>
        <div style="flex: 2; min-width: 300px;">
            <h1 style="color: var(--primary-color); font-size: 3rem; margin-bottom: 10px;">
                <?php echo htmlspecialchars($equipo['nombre']); ?>
            </h1>
            <h3 style="margin-bottom: 20px;">
                <i class="fa-solid fa-motorcycle"></i> <?php echo $equipo['marca']; ?> 
                <span style="color: #888; margin-left:15px; font-size: 0.8em; font-weight: normal;">
                    <i class="fa-solid fa-earth-europe"></i> <?php echo $equipo['pais']; ?>
                </span>
                <?php if ($equipo['ano_fundacion']): ?>
                    <span style="color: #888; margin-left:15px; font-size: 0.8em; font-weight: normal;">
                        <i class="fa-regular fa-calendar-days"></i> Fundado en: <?php echo $equipo['ano_fundacion']; ?>
                    </span>
                <?php endif; ?>
            </h3>

            <div style="background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <p style="color: #ddd; line-height: 1.6;">
                    <?php echo $equipo['descripcion'] ? nl2br(htmlspecialchars($equipo['descripcion'])) : '<em>Sin descripción disponible actualmente.</em>'; ?>
                </p>
            </div>
            
            <a href="equipos.php" class="btn-primary" style="background: #555; text-decoration: none; padding: 10px 20px;">Volver al listado</a>
            <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin'): ?>
                <a href="admin_equipo.php?id=<?php echo $equipo['id']; ?>" class="btn-primary" style="margin-left: 10px; text-decoration: none; padding: 10px 20px;">Editar</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sección de Pilotos -->
    <div style="margin-top: 40px;">
        <h2><i class="fa-solid fa-helmet-safety"></i> Pilotos del Equipo</h2>
        <div class="grid-container" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
            <?php if (mysqli_num_rows($res_pilotos) > 0): ?>
                <?php while($piloto = mysqli_fetch_assoc($res_pilotos)): ?>
                    <div class="card" style="text-align: center;">
                        <a href="ver_piloto.php?id=<?php echo $piloto['id']; ?>" style="text-decoration: none; color: white;">
                            <?php 
                                $p_img = $piloto['imagen_url'];
                                if (!$p_img) $p_img = 'https://via.placeholder.com/300x400';
                            ?>
                            <img src="<?php echo $p_img; ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid var(--primary-color); margin-bottom: 10px;">
                            <h3 style="font-size: 1.1em;"><?php echo htmlspecialchars($piloto['nombre']); ?></h3>
                            <span style="color: var(--primary-color); font-weight: bold;">#<?php echo $piloto['dorsal']; ?></span>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Este equipo no tiene pilotos asignados todavía.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
