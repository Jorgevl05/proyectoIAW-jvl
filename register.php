<?php
include 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, trim($_POST['nombre']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password']; // No escapamos aún porque vamos a hashear
    $confirm_password = $_POST['confirm_password'];

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Formato de email inválido.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Comprobar si existe el email
        $check_sql = "SELECT id FROM usuarios WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Este email ya está registrado.";
        } else {
            // Insertar usuario
            // Encriptar contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Por defecto rol 'fan'
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES ('$nombre', '$email', '$hashed_password', 'fan')";
            
            if (mysqli_query($conn, $sql)) {
                header("Location: login.php?msg=Registro completado con éxito. Inicia sesión.&type=success");
                exit();
            } else {
                $error = "Error en base de datos: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!-- Usamos un header simplificado si no tenemos sesión, o el normal -->
<?php include 'header.php'; ?>

<div class="auth-container">
    <h2 class="text-center" style="color: var(--primary-color);">Crear Cuenta</h2>
    
    <?php if ($error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" required value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label>Confirmar Contraseña:</label>
            <input type="password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn-primary">Registrarse</button>
    </form>
    
    <p class="text-center" style="margin-top: 20px;">
        ¿Ya tienes cuenta? <a href="login.php" style="color: var(--primary-color);">Inicia Sesión</a>
    </p>
</div>

<?php include 'footer.php'; ?>
