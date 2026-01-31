<?php
include 'header.php';

$error_msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : "Ha ocurrido un error desconocido.";
$type = isset($_GET['type']) ? $_GET['type'] : 'error'; // error, warning, info

$color = '#ff4444';
$icon = 'fa-circle-exclamation';

if ($type == 'success') {
    $color = '#00C851';
    $icon = 'fa-circle-check';
}
?>

<div style="text-align: center; padding: 50px;">
    <i class="fa-solid <?php echo $icon; ?>" style="font-size: 4rem; color: <?php echo $color; ?>; margin-bottom: 20px;"></i>
    <h2 style="margin-bottom: 20px;">Atención</h2>
    <div class="auth-container" style="background: rgba(255,255,255,0.05); max-width: 600px;">
        <p style="font-size: 1.2rem;"><?php echo $error_msg; ?></p>
    </div>
    <br>
    <button onclick="window.history.back()" class="btn-primary" style="width: auto; display: inline-block;">Volver Atrás</button>
    <a href="index.html" class="btn-primary" style="width: auto; display: inline-block; background-color: #333; margin-left: 10px;">Ir al Inicio</a>
</div>

<?php include 'footer.php'; ?>
