<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

// Puedes cambiar el título dinámicamente usando $titulo
$titulo = $titulo ?? 'Panel';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-md">
        <?php include 'header.php'; ?>

        <!-- Aquí se mostrará el contenido principal -->
        <?php include $contenido; ?>

        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
