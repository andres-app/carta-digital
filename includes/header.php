<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$nombre = $_SESSION['nombre'] ?? 'Usuario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<!-- 🔶 CABECERA -->
<header class="fixed top-0 left-0 right-0 z-40 bg-white border-b border-gray-200 shadow flex items-center justify-between px-4 md:px-6 h-16">
    <!-- Hamburguesa -->
    <button id="toggleSidebar" class="md:hidden p-2 rounded hover:bg-gray-100 transition">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Logo -->
    <div class="flex items-center gap-2">
        <img src="../assets/img/imagen_logoh.png" alt="Logo" class="h-10 w-auto object-contain" />
    </div>

    <!-- Usuario y logout -->
    <div class="flex items-center gap-4 text-sm">
        <span class="hidden sm:inline text-gray-600 font-medium"><?= htmlspecialchars($nombre) ?></span>
        <a href="../logout.php" class="text-red-500 hover:text-red-700 font-semibold">Salir</a>
    </div>
</header>

<!-- Contenedor Principal -->
<div class="flex pt-16"> <!-- pt-16 por la cabecera fija -->
