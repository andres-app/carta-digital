<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: admin/dashboard.php");
    exit;
}
$error = $_SESSION['registro_error'] ?? null;
unset($_SESSION['registro_error']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-[#FFF5E1] to-[#fbe7d3] min-h-screen flex items-center justify-center">

    <div class="bg-white/90 shadow-xl p-10 rounded-3xl w-full max-w-md text-center">
        <h2 class="text-2xl font-bold mb-6 text-orange-600">Crear nueva cuenta</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-6 text-sm text-center animate-pulse">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="procesar_registro.php" class="space-y-5 text-left">
            <div>
                <label class="block mb-1 font-semibold text-[#3A1F0F] text-sm">Nombre completo</label>
                <input type="text" name="nombre" placeholder="Tu nombre" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>
            <div>
                <label class="block mb-1 font-semibold text-[#3A1F0F] text-sm">Correo electrónico</label>
                <input type="email" name="email" placeholder="correo@ejemplo.com" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>
            <div>
                <label class="block mb-1 font-semibold text-[#3A1F0F] text-sm">Contraseña</label>
                <input type="password" name="password" placeholder="********" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>
            <button type="submit"
                class="w-full bg-[#E94E2C] hover:bg-[#cc3f20] text-white font-bold py-2 rounded-lg transition">
                Registrarme
            </button>
            <a href="login.php" class="block mt-2 text-sm text-indigo-700 hover:underline text-center">
                Ya tengo cuenta
            </a>
        </form>
    </div>
</body>
</html>
