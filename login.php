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
    <title>TuCarta | Iniciar Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-indigo-100 to-white min-h-screen flex items-center justify-center">

    <div class="bg-white/60 backdrop-blur-md shadow-xl p-10 rounded-3xl w-full max-w-md text-center">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="./assets/img/logo-tucarta2.png" alt="TuCarta Logo" class="w-48 h-48 object-contain">
        </div>

        <!-- Slogan -->
        <p class="text-gray-600 text-sm mb-8 italic">Tu carta digital en un solo clic</p>

        <!-- Formulario -->
        <form method="POST" action="procesar_login.php" class="space-y-5 text-left">
            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">Correo electrónico</label>
                <input type="email" name="email" placeholder="correo@ejemplo.com" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">Contraseña</label>
                <input type="password" name="password" placeholder="********" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition">Iniciar Sesión</button>
        </form>
    </div>

</body>
</html>
