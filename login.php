<?php
// Incluir header primero (inicia sesión PHP)
include 'header.php'; 
include 'db.php';

// Si ya está logueado, redirigir
if (isset($_SESSION['user_id'])) {
    header("Location: pilotos.php");
    exit();
}

$error = '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$msg_type = isset($_GET['type']) ? $_GET['type'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Rellena todos los campos.";
    } else {
        $sql = "SELECT id, nombre, password, rol FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                // Login correcto set variables de sesión
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_nombre'] = $row['nombre'];
                $_SESSION['user_email'] = $email;
                $_SESSION['user_rol'] = $row['rol']; // 'admin' o 'fan'

                // Redirección según rol o página general
                header("Location: pilotos.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "No existe cuenta con ese email.";
        }
    }
}
?>

<div class="auth-container">
    <h2 class="text-center" style="color: var(--primary-color);">Iniciar Sesión</h2>
    
    <?php if ($msg): ?>
        <div style="background: <?php echo $msg_type == 'success' ? '#00C851' : '#33b5e5'; ?>; color: white; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password" required>
        </div>
        
        <button type="submit" class="btn-primary">Entrar</button>
    </form>
    
    <p class="text-center" style="margin-top: 20px;">
        ¿No tienes cuenta? <a href="register.php" style="color: var(--primary-color);">Regístrate</a>
    </p>
</div>

<?php include 'footer.php'; ?>
