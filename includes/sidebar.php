<?php include_once __DIR__ . '/../config.php'; ?>
<aside class="fixed top-0 left-0 h-screen w-60 bg-white border-r border-gray-100 shadow z-30 flex flex-col">
    <div class="h-20 flex items-center justify-center border-b border-gray-100">
        <img src="../assets/img/imagen_logoh.png" alt="Logo" class="h-12 w-auto object-contain" />
    </div>
    <nav class="flex-1 px-3 py-6">
        <ul class="space-y-2">
            <li>
                <a href="<?= $base_url ?>/admin/dashboard.php" class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 font-medium hover:bg-orange-50 hover:text-orange-600 transition">
                    <span>🏠</span> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= $base_url ?>/admin/mis_locales.php" class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 font-medium hover:bg-orange-50 hover:text-orange-600 transition">
                    <span>🏬</span> Mis Locales
                </a>
            </li>
            <!-- Otros menús -->
        </ul>
    </nav>
    <div class="px-4 py-4 border-t border-gray-100 flex items-center gap-2 text-xs text-gray-500">
        <?= htmlspecialchars($nombre) ?> (<?= $panel ?>)
        <a href="../logout.php" class="ml-auto text-red-500 hover:text-red-700" title="Cerrar sesión">
            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
            </svg>
        </a>
    </div>
</aside>
