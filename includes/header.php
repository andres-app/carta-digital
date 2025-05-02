<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$nombre = $_SESSION['nombre'] ?? 'Usuario';
$rol = $_SESSION['rol'] ?? 'admin';

$panel = $rol === 'superadmin' ? 'Panel de Superadmin' : 'Panel de Cliente';
?>

<div class="flex justify-between items-center w-full">
    <!-- Logo y panel -->
    <div class="flex items-center gap-3">
        <img src="../assets/img/imagen_logoh.png" alt="Logo" class="w-40 object-contain">
    </div>

    <!-- Usuario y cerrar sesión -->
    <div class="flex items-center gap-4 text-sm text-gray-800">
        <span class="flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z" />
            </svg>
            <?= htmlspecialchars($nombre) ?>
        </span>

        <a href="../logout.php" title="Cerrar sesión" class="text-red-600 hover:text-red-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
            </svg>
        </a>
    </div>
</div>