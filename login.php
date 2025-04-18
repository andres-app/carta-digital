<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['rol'] === 'superadmin') {
        header("Location: superadmin/index.php");
    } else {
        header("Location: admin/dashboard.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">🔐 Iniciar Sesión</h1>
        <form method="POST" action="procesar_login.php" class="space-y-4">
            <input type="email" name="email" placeholder="Correo" class="w-full p-2 border rounded" required>
            <input type="password" name="password" placeholder="Contraseña" class="w-full p-2 border rounded" required>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Ingresar</button>
        </form>
    </div>
</body>
</html>
