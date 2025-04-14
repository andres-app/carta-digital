<!-- public/index.php -->
<?php
require_once '../includes/db.php';
// Aquí suponemos que llega un ID por GET o subdominio
$restaurant_id = $_GET['r'] ?? 0;
// Obtener datos del restaurante y carta
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carta Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">
    <div class="max-w-4xl mx-auto p-4">
        <!-- Nombre del restaurante -->
        <h1 class="text-3xl font-bold text-center mb-6">[Nombre del Restaurante]</h1>

        <!-- Categorías -->
        <div class="space-y-6">
            <!-- Suponiendo que obtenemos categorías y platos desde DB -->
            <div class="border-b pb-4">
                <h2 class="text-xl font-semibold text-red-600">Entradas</h2>
                <ul class="mt-2 space-y-2">
                    <li class="flex justify-between">
                        <span>Ensalada César</span>
                        <span>S/ 15.00</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Tequeños</span>
                        <span>S/ 12.00</span>
                    </li>
                </ul>
            </div>

            <div class="border-b pb-4">
                <h2 class="text-xl font-semibold text-green-600">Platos Principales</h2>
                <ul class="mt-2 space-y-2">
                    <li class="flex justify-between">
                        <span>Lomo Saltado</span>
                        <span>S/ 25.00</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Aji de Gallina</span>
                        <span>S/ 20.00</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
