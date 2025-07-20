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
    <!-- Botón hamburguesa -->
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

    <!-- Menú de usuario -->
    <div class="relative inline-block text-left">
        <button id="userMenuButton" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none focus:ring">
            <span class="hidden sm:inline"><?= htmlspecialchars($nombre) ?></span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z"/>
            </svg>
        </button>
        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">👤 Mi perfil</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">💬 Soporte</a>
            <a href="../logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">🚪 Cerrar sesión</a>
        </div>
    </div>
</header>

<!-- Contenedor principal con padding superior para compensar el header fijo -->
<div class="flex pt-16">

<!-- Script del menú desplegable -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('userMenuButton');
    const dropdown = document.getElementById('userDropdown');

    btn.addEventListener('click', (e) => {
      e.stopPropagation(); // prevenir cierre inmediato
      dropdown.classList.toggle('hidden');
    });

    // Ocultar el menú al hacer clic fuera
    document.addEventListener('click', () => {
      dropdown.classList.add('hidden');
    });
  });
</script>
