<?php
if (session_status() === PHP_SESSION_NONE)
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

</head>

<body class="bg-gradient-to-br from-[#FFF5E1] to-[#fbe7d3] min-h-screen flex items-center justify-center">

    <div class="flex flex-col md:flex-row items-center justify-center w-full max-w-7xl p-6 md:gap-x-12">

        <!-- Imagen lateral (solo en escritorio) -->
        <div class="hidden md:block md:w-3/5">
            <img src="./assets//img/imagen_login.png" alt="Imagen lateral"
                class="w-full h-auto rounded-3xl shadow-2xl object-cover">
        </div>

        <!-- Formulario login -->
        <div class="bg-[#FFF5E1]/90 backdrop-blur-md shadow-xl p-10 rounded-3xl w-full max-w-md text-center">
            <!-- Logo -->
            <div class="flex justify-center">
                <img src="./assets/img/qarta_logo.png" alt="TuCarta Logo" class="w-48 h-48 object-contain">
            </div>

            <!-- Slogan -->
            <p class="text-gray-600 text-sm italic">Tu carta digital en un solo clic</p>

            <!-- Mensaje de error -->
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-6 text-sm text-center animate-pulse">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form method="POST" action="procesar_login.php" class="space-y-5 text-left">
                <div>
                    <label class="block mb-1 font-semibold text-[#3A1F0F] text-sm">Correo electrónico</label>
                    <input type="email" name="email" placeholder="correo@ejemplo.com" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-[#3A1F0F] text-sm">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="********" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 outline-none pr-10">
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-2 flex items-center text-gray-500 text-sm focus:outline-none">
                            👁️
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-[#E94E2C] hover:bg-[#cc3f20] text-white font-bold py-2 rounded-lg transition">

                    Iniciar Sesión
                </button>
            </form>
        </div>

    </div>

    <!-- JS para mostrar/ocultar contraseña -->
    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>

</body>

</html>