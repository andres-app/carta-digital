<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$nombre = $_SESSION['nombre'] ?? 'Usuario';
$rol = $_SESSION['rol'] ?? 'admin';

$panel = $rol === 'superadmin' ? 'Panel de Superadmin' : 'Panel de Cliente';
?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= $panel ?> - <?= htmlspecialchars($nombre) ?></h1>
    <a href="../logout.php" class="text-sm text-red-600 hover:underline">Cerrar sesión</a>
</div>
