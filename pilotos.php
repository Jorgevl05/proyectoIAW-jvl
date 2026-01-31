<?php
include 'header.php';
include 'db.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = ($_SESSION['user_rol'] === 'admin');

// Inicializar variables de búsqueda
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'puntos_desc';

// Construir Query
$sql = "SELECT p.*, e.nombre as nombre_equipo, e.marca 
        FROM pilotos p 
        LEFT JOIN equipos e ON p.id_equipo = e.id 
        WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (p.nombre LIKE '%$search%' OR e.nombre LIKE '%$search%')";
}

switch ($order) {
    case 'nombre_asc': $sql .= " ORDER BY p.nombre ASC"; break;
    case 'dorsal_asc': $sql .= " ORDER BY p.dorsal ASC"; break;
    case 'puntos_desc': $sql .= " ORDER BY p.puntos DESC"; break; // Default: Puntos Descendente (Clasificación)
    default: $sql .= " ORDER BY p.puntos DESC";
}

$result = mysqli_query($conn, $sql);
?>

<div class="row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2><i class="fa-solid fa-helmet-safety"></i> Parrilla de Pilotos</h2>
    <?php if ($isAdmin): ?>
        <a href="admin_piloto.php" class="btn-primary" style="width: auto;"><i class="fa-solid fa-plus"></i> Añadir Piloto</a>
    <?php endif; ?>
</div>

<div class="search-bar" style="background: #2c2c2c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
    <form action="pilotos.php" method="GET" style="display: flex; gap: 10px; align-items: center;">
        <input type="text" name="search" placeholder="Buscar por piloto o equipo..." value="<?php echo htmlspecialchars($search); ?>" style="flex-grow: 1;">
        
        <select name="order" style="width: 200px;">
            <option value="puntos_desc" <?php if($order=='puntos_desc') echo 'selected'; ?>>Clasificación (Puntos)</option>
            <option value="nombre_asc" <?php if($order=='nombre_asc') echo 'selected'; ?>>Nombre (A-Z)</option>
            <option value="dorsal_asc" <?php if($order=='dorsal_asc') echo 'selected'; ?>>Dorsal</option>
        </select>
        
        <button type="submit" class="btn-primary" style="width: auto;">Filtrar</button>
        <?php if($search || $order != 'puntos_desc'): ?>
            <a href="pilotos.php" class="btn-primary" style="background: #555; width: auto; text-align:center; padding-top:12px;">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<div class="grid-container">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <!-- Imagen placeholder si no hay url -->
                <?php 
                    $img = $row['imagen_url'];
                    if (!$img) {
                        $img = 'https://via.placeholder.com/300x200?text=No+Image';
                    }
                ?>
                <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                
                <div class="card-content">
                    <div class="card-title">
                        #<?php echo $row['dorsal']; ?> <?php echo htmlspecialchars($row['nombre']); ?>
                    </div>
                    <p style="color: #aaa; margin-bottom: 5px;">
                        <i class="fa-solid fa-flag"></i> <?php echo htmlspecialchars($row['pais']); ?>
                    </p>
                    <p style="margin-bottom: 5px;">
                        <i class="fa-solid fa-motorcycle"></i> <?php echo htmlspecialchars($row['nombre_equipo']); ?> (<?php echo $row['marca']; ?>)
                    </p>
                    <p style="font-weight: bold; color: white; font-size: 1.1em;">
                        <i class="fa-solid fa-trophy"></i> <?php echo $row['puntos']; ?> pts
                    </p>

                    <a href="ver_piloto.php?id=<?php echo $row['id']; ?>" class="btn-primary btn-small" style="display: block; text-align: center; margin-top: 10px; margin-bottom: 10px; text-decoration: none;">Ver / Comentar</a>
                    
                    <?php if ($isAdmin): ?>
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <a href="admin_piloto.php?id=<?php echo $row['id']; ?>" class="btn-small btn-edit" style="text-decoration: none; color: white;">Editar</a>
                            <a href="borrar_piloto.php?id=<?php echo $row['id']; ?>" class="btn-small btn-danger" style="text-decoration: none; color: white;" onclick="return confirm('¿Seguro que quieres eliminar a este piloto?');">Borrar</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron pilotos.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
