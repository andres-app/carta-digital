<?php
session_start();
require "../includes/header.php";
require "../includes/sidebar.php";
require_once '../includes/db.php';
require_once '../includes/helpers.php';

$empresa_id = $_SESSION['empresa_id'] ?? null;

// Consultas principales
$stmt = $conn->prepare("SELECT * FROM locales WHERE empresa_id = ?");
$stmt->execute([$empresa_id]);
$locales = $stmt->fetchAll();
$total_locales = count($locales);

$stmt = $conn->prepare("SELECT plan FROM empresas WHERE id = ?");
$stmt->execute([$empresa_id]);
$plan = $stmt->fetchColumn();
$limite = obtenerLimiteLocalesPorPlan($plan);
$disponibles = $limite - $total_locales;

$stmt = $conn->prepare("SELECT COUNT(*) FROM platos WHERE categoria_id IN (SELECT id FROM categorias WHERE local_id IN (SELECT id FROM locales WHERE empresa_id = ?))");
$stmt->execute([$empresa_id]);
$total_platos = $stmt->fetchColumn();

$nombre = $_SESSION['nombre'] ?? 'Usuario';
?>

<main class="min-h-screen px-4 py-10 transition-all duration-300 md:ml-60 w-full max-w-5xl mx-auto">
    <div class="max-w-6xl mx-auto space-y-8">

        <!-- Bienvenida -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-[#3A1F0F]">Bienvenido, <?= htmlspecialchars($nombre) ?> 👋</h1>
                <p class="text-gray-500 text-base mt-1">Este es tu panel de control. Aquí tienes un resumen de tu actividad y accesos rápidos.</p>
            </div>
            <div>
                <a href="mis_locales.php" class="inline-block bg-orange-600 text-white px-5 py-2 rounded-lg hover:bg-orange-700 transition text-sm font-semibold shadow">
                    <span class="inline-block align-middle">🏬</span> Ver mis Locales
                </a>
            </div>
        </div>

        <!-- KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <span class="text-orange-500 text-4xl font-bold"><?= $total_locales ?></span>
                <div class="text-xs uppercase text-gray-400 font-semibold mt-1 tracking-widest">Locales activos</div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <span class="text-green-600 text-4xl font-bold"><?= $total_platos ?></span>
                <div class="text-xs uppercase text-gray-400 font-semibold mt-1 tracking-widest">Platos registrados</div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <span class="text-blue-600 text-4xl font-bold"><?= number_format($limite > 0 ? ($total_locales * 100 / $limite) : 0, 0) ?>%</span>
                <div class="text-xs uppercase text-gray-400 font-semibold mt-1 tracking-widest">Uso del Plan</div>
            </div>
        </div>

        <!-- Info del plan -->
        <div class="p-4 bg-[#FFF5E1] border border-[#E94E2C]/20 rounded-lg text-base text-[#3A1F0F] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p><strong>Plan actual:</strong> <?= ucfirst($plan) ?></p>
                <p><strong>Locales usados:</strong> <?= $total_locales ?> / <?= $limite ?>
                    <?php if ($disponibles > 0): ?>
                        — <span class="text-green-600 font-semibold"><?= $disponibles ?> disponible<?= $disponibles > 1 ? 's' : '' ?></span>
                    <?php else: ?>
                        — <span class="text-red-600 font-semibold">Límite alcanzado</span>
                    <?php endif; ?>
                </p>
            </div>
            <a href="editar_carta.php?local_id=<?= $locales[0]['id'] ?? 0 ?>" class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-100 transition text-sm font-semibold shadow">
                <svg class="w-5 h-5 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16.862 3.487a2.25 2.25 0 113.182 3.182l-11.07 11.07a4.5 4.5 0 01-1.988 1.164l-3.381.96.96-3.38a4.5 4.5 0 011.164-1.988l11.07-11.07z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Editar Carta
            </a>
        </div>

        <!-- Gráfica rápida -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-semibold text-lg mb-4 text-gray-700">Actividad reciente</h2>
            <div class="w-full flex justify-center">
                <img src="https://quickchart.io/chart?c={type:'bar',data:{labels:['Ene','Feb','Mar','Abr','May','Jun'],datasets:[{label:'Platos agregados',data:[12,19,3,5,2,3]}]}}" alt="Actividad reciente" class="w-full max-w-lg mx-auto rounded-xl shadow">
            </div>
        </div>

        <!-- Atajos rápidos -->
        <div class="mb-8 flex flex-wrap gap-4">
            <a href="mis_locales.php" class="flex items-center gap-2 bg-orange-50 text-orange-700 px-4 py-2 rounded-lg hover:bg-orange-100 transition shadow">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 9.75V18a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18V9.75M12 3v8.25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Ver Locales
            </a>
            <a href="editar_carta.php?local_id=<?= $locales[0]['id'] ?? 0 ?>" class="flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-100 transition shadow">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16.862 3.487a2.25 2.25 0 113.182 3.182l-11.07 11.07a4.5 4.5 0 01-1.988 1.164l-3.381.96.96-3.38a4.5 4.5 0 011.164-1.988l11.07-11.07z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Editar Carta
            </a>
            <!-- Puedes añadir más atajos aquí -->
        </div>

        <!-- Avisos y soporte -->
        <div class="bg-yellow-50 border-l-4 border-yellow-300 text-yellow-800 p-4 rounded-xl shadow">
            <strong class="block mb-1">¿Necesitas ayuda?</strong>
            Puedes contactarnos al <a href="mailto:soporte@qarta.pe" class="underline">soporte@qarta.pe</a> o ver la <a href="#" class="underline">guía de uso</a>.
        </div>

    </div>
</main>
<?php require "../includes/footer.php"; ?>
