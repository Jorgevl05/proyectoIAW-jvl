<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoGP App</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="index.html" class="logo"><i class="fa-solid fa-motorcycle"></i> MotoGP App</a>
            <nav>
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Menú para usuarios logueados -->
                        <li><a href="pilotos.php">Pilotos</a></li>
                        <li><a href="equipos.php">Equipos</a></li>
                        
                        <?php if ($_SESSION['user_rol'] === 'admin'): ?>
                            <!-- Opciones extra para Admin -->
                            <li><a href="gestion_pilotos.php" style="color: #ff8800;">Panel Admin</a></li>
                        <?php endif; ?>

                        <li style="margin-left: 20px; border-left: 1px solid #444; padding-left: 20px;">
                            <span style="font-size: 0.9em; color: #aaa;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?> (<?php echo ucfirst($_SESSION['user_rol']); ?>)</span>
                        </li>
                        <li><a href="logout.php" class="btn-small btn-danger"><i class="fa-solid fa-right-from-bracket"></i> Salir</a></li>
                    
                    <?php else: ?>
                        <!-- Menú para visitantes (si acceden a páginas php públicas) -->
                        <li><a href="index.html">Inicio</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Registro</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container main-content">
        <!-- El contenido específico de cada página irá aquí -->
