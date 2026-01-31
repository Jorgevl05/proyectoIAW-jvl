<?php
include 'header.php';
include 'db.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = ($_SESSION['user_rol'] === 'admin');

// Query simple de equipos
$sql = "SELECT * FROM equipos ORDER BY nombre ASC";
$result = mysqli_query($conn, $sql);
?>

<div class="row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2><i class="fa-solid fa-users-gear"></i> Equipos Oficiales</h2>
    <?php if ($isAdmin): ?>
        <a href="admin_equipo.php" class="btn-primary" style="width: auto;"><i class="fa-solid fa-plus"></i> Añadir Equipo</a>
    <?php endif; ?>
</div>

<div class="grid-container">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <!-- Imagen placeholder si no hay url o ajuste para img local -->
                <?php 
                    $img = $row['imagen_url'];
                    if (!$img) {
                        $img = 'https://via.placeholder.com/300x200?text=Logo+Equipo';
                    }
                ?>
                <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>" style="object-fit: contain; padding: 20px; background: #fff; width: 100%; height: 200px;">
                
                <div class="card-content">
                    <div class="card-title">
                        <a href="ver_equipo.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: white; display: block;">
                            <?php echo htmlspecialchars($row['nombre']); ?>
                        </a>
                    </div>
                    <p style="margin-bottom: 5px;">
                        <i class="fa-solid fa-motorcycle"></i> Marca: <?php echo htmlspecialchars($row['marca']); ?>
                    </p>
                    <p style="color: #aaa; margin-bottom: 15px;">
                        <i class="fa-solid fa-earth-europe"></i> <?php echo htmlspecialchars($row['pais']); ?>
                    </p>
                    
                    <!-- Sección de Pilotos del Equipo -->
                    <div style="border-top: 1px solid #444; padding-top: 10px; margin-top: 10px;">
                        <h4 style="font-size: 0.9em; color: #888; margin-bottom: 5px;">Pilotos:</h4>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <?php
                            $team_id = $row['id'];
                            $pilotos_sql = "SELECT id, nombre, imagen_url FROM pilotos WHERE id_equipo = $team_id";
                            $pilotos_result = mysqli_query($conn, $pilotos_sql);
                            if (mysqli_num_rows($pilotos_result) > 0):
                                while($piloto = mysqli_fetch_assoc($pilotos_result)):
                                    $p_img = $piloto['imagen_url'];
                                    if (!$p_img) $p_img = 'https://via.placeholder.com/50';
                            ?>
                                <a href="ver_piloto.php?id=<?php echo $piloto['id']; ?>" style="text-decoration: none; text-align: center;" title="<?php echo htmlspecialchars($piloto['nombre']); ?>">
                                    <img src="<?php echo $p_img; ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-color);">
                                    <div style="font-size: 0.7em; color: white; max-width: 50px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                        <?php echo htmlspecialchars($piloto['nombre']); ?>
                                    </div>
                                </a>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                                <span style="font-size: 0.8em; color: #666;">Sin pilotos asignados</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="margin-top: 15px; margin-bottom: 5px;">
                        <a href="ver_equipo.php?id=<?php echo $row['id']; ?>" class="btn-small" style="background: #444; color: white; text-decoration: none; padding: 5px 10px; font-size: 0.8em; border-radius: 4px;">Ver Detalles</a>
                    </div>

                    <?php if ($isAdmin): ?>
                        <div style="margin-top: 20px; display: flex; gap: 10px;">
                            <a href="admin_equipo.php?id=<?php echo $row['id']; ?>" class="btn-small btn-edit" style="text-decoration: none; color: white;">Editar</a>
                            <a href="borrar_equipo.php?id=<?php echo $row['id']; ?>" class="btn-small btn-danger" style="text-decoration: none; color: white;" onclick="return confirm('¿Eliminar equipo?');">Borrar</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron equipos.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
