<?php
include 'header.php';
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: pilotos.php");
    exit();
}

$id_piloto = (int)$_GET['id'];

// Obtener info del piloto y su equipo
$sql = "SELECT p.*, e.nombre as nombre_equipo, e.marca 
        FROM pilotos p 
        LEFT JOIN equipos e ON p.id_equipo = e.id 
        WHERE p.id = $id_piloto";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='container'><p>Piloto no encontrado</p></div>";
    include 'footer.php';
    exit();
}

$piloto = mysqli_fetch_assoc($result);

// Obtener comentarios
$sql_comentarios = "SELECT c.*, u.nombre as nombre_usuario 
                    FROM comentarios c 
                    JOIN usuarios u ON c.id_usuario = u.id 
                    WHERE c.id_piloto = $id_piloto 
                    ORDER BY c.fecha DESC";
$res_comentarios = mysqli_query($conn, $sql_comentarios);
?>

<div class="container" style="margin-top: 30px;">
    <!-- Detalles del Piloto -->
    <div style="display: flex; gap: 40px; background: #2c2c2c; padding: 30px; border-radius: 10px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <?php 
                $img = $piloto['imagen_url'];
                if (!$img) {
                    $img = 'https://via.placeholder.com/300x400?text=Foto+Piloto';
                }
            ?>
            <img src="<?php echo $img; ?>" style="width: 100%; border-radius: 10px;">
        </div>
        <div style="flex: 2; min-width: 300px;">
            <h1 style="color: var(--primary-color); font-size: 3rem;">
                <span style="color: white; font-size: 0.5em;">#<?php echo $piloto['dorsal']; ?></span> 
                <?php echo htmlspecialchars($piloto['nombre']); ?>
            </h1>
            <h3>
                Equipo: 
                <a href="ver_equipo.php?id=<?php echo $piloto['id_equipo']; ?>" style="color: var(--primary-color); text-decoration: none;">
                    <?php echo htmlspecialchars($piloto['nombre_equipo']); ?> (<?php echo $piloto['marca']; ?>)
                </a>
            </h3>
            <p><strong>Nacionalidad:</strong> <?php echo $piloto['pais']; ?></p>
            <p><strong>Puntos Campeonato:</strong> <?php echo $piloto['puntos']; ?></p>
            
            <div style="margin-top: 20px; display: flex; gap: 20px;">
                <div style="background: rgba(255,255,255,0.1); padding: 10px 20px; border-radius: 5px;">
                    <i class="fa-solid fa-trophy" style="color: gold;"></i> 
                    <strong><?php echo $piloto['titulos']; ?></strong> Títulos Mundiales
                </div>
                <?php if ($piloto['ano_debut']): ?>
                    <div style="background: rgba(255,255,255,0.1); padding: 10px 20px; border-radius: 5px;">
                        <i class="fa-regular fa-calendar-days"></i> 
                        Debut: <strong><?php echo $piloto['ano_debut']; ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 20px; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px;">
                <h4 style="margin-top: 0;">Biografía</h4>
                <p style="color: #ddd; line-height: 1.6;">
                    <?php echo $piloto['biografia'] ? nl2br(htmlspecialchars($piloto['biografia'])) : '<em>Biografía no disponible.</em>'; ?>
                </p>
            </div>

            <br>
            <a href="pilotos.php" class="btn-primary" style="background: #555; text-decoration: none; padding: 10px 20px;">Volver al listado</a>
        </div>
    </div>

    <!-- Sección de Comentarios -->
    <div style="margin-top: 40px;">
        <h2><i class="fa-regular fa-comments"></i> Comentarios de los Fans</h2>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div style="background: #333; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                <h4>Deja tu opinión</h4>
                <form action="comentar.php" method="POST">
                    <input type="hidden" name="id_piloto" value="<?php echo $id_piloto; ?>">
                    <div class="form-group">
                        <textarea name="comentario" rows="3" required placeholder="Escribe aquí tu comentario..." style="width: 100%; padding: 10px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Valoración:</label>
                        <select name="valoracion" style="width: 150px;">
                            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                            <option value="4">⭐⭐⭐⭐ (4)</option>
                            <option value="3">⭐⭐⭐ (3)</option>
                            <option value="2">⭐⭐ (2)</option>
                            <option value="1">⭐ (1)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary" style="width: auto;">Publicar Comentario</button>
                </form>
            </div>
        <?php else: ?>
            <div style="background: #333; padding: 20px; margin-bottom: 30px;">
                <p><a href="login.php" style="color: var(--primary-color);">Inicia sesión</a> para comentar.</p>
            </div>
        <?php endif; ?>

        <!-- Listado de Comentarios -->
        <div>
            <?php if (mysqli_num_rows($res_comentarios) > 0): ?>
                <?php while($com = mysqli_fetch_assoc($res_comentarios)): ?>
                    <div style="background: #1f1f1f; padding: 15px; margin-bottom: 15px; border-radius: 5px; border-left: 4px solid var(--primary-color);">
                        <div style="display: flex; justify-content: space-between;">
                            <strong><?php echo htmlspecialchars($com['nombre_usuario']); ?></strong>
                            <span style="color: gold;">
                                <?php for($i=0; $i<$com['valoracion']; $i++) echo '★'; ?>
                            </span>
                        </div>
                        <p style="margin-top: 10px; color: #ddd;"><?php echo nl2br(htmlspecialchars($com['comentario'])); ?></p>
                        <small style="color: #666;"><?php echo $com['fecha']; ?></small>
                        
                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $com['id_usuario'] || $_SESSION['user_rol'] == 'admin')): ?>
                            <!-- Botón borrar para el autor o admin -->
                            <div style="margin-top: 5px; text-align: right;">
                                <a href="borrar_comentario.php?id=<?php echo $com['id']; ?>&piloto=<?php echo $id_piloto; ?>" style="color: #ff4444; font-size: 0.8rem;">Eliminar</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aún no hay comentarios. ¡Sé el primero!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
