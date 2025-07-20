<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$rol = $_SESSION['rol'] ?? 'admin';
$panel = $rol === 'superadmin' ? 'Panel de Superadmin' : 'Panel de Cliente';
?>

<aside id="sidebar"
       class="fixed top-16 left-0 h-[calc(100vh-4rem)] w-60 bg-white border-r border-gray-100 shadow z-30 flex flex-col transform transition-transform duration-300 md:translate-x-0 -translate-x-full md:static md:block">
    <nav class="flex-1 px-3 py-6">
        <ul class="space-y-2">
            <li><a href="dashboard.php"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 font-medium hover:bg-orange-50 hover:text-orange-600 transition">🏠 Dashboard</a>
            </li>
            <li><a href="mis_locales.php"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 font-medium hover:bg-orange-50 hover:text-orange-600 transition">🏬 Mis
                    Locales</a></li>
        </ul>
    </nav>
    <div class="px-4 py-4 border-t border-gray-100 text-xs text-gray-500">
        <?= htmlspecialchars($panel) ?>
    </div>
</aside>
