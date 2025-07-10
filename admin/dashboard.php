<?php
session_start();
require "../includes/header.php";
require_once '../includes/db.php';
require_once '../includes/helpers.php';

$empresa_id = $_SESSION['empresa_id'];

// Obtener datos del plan y locales
$stmt = $conn->prepare("SELECT * FROM locales WHERE empresa_id = ?");
$stmt->execute([$empresa_id]);
$locales = $stmt->fetchAll();
$total_locales = count($locales);

$stmt = $conn->prepare("SELECT plan FROM empresas WHERE id = ?");
$stmt->execute([$empresa_id]);
$plan = $stmt->fetchColumn();
$limite = obtenerLimiteLocalesPorPlan($plan);
$disponibles = $limite - $total_locales;

// Platos totales
$stmt = $conn->prepare("SELECT COUNT(*) FROM platos WHERE categoria_id IN (SELECT id FROM categorias WHERE local_id IN (SELECT id FROM locales WHERE empresa_id = ?))");
$stmt->execute([$empresa_id]);
$total_platos = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Carta.pe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php require "../includes/header.php"; ?>

<main class="ml-60 min-h-screen px-4 py-10">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-[#3A1F0F] mb-6">Bienvenido a tu Panel</h1>

        <!-- Info del plan -->
        <div class="mb-8 p-4 bg-[#FFF5E1] border border-[#E94E2C]/20 rounded-lg text-base text-[#3A1F0F] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
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
            <a href="mis_locales.php" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition text-sm font-semibold shadow">Ir a Mis Locales</a>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <span class="text-orange-500 text-4xl font-bold"><?= $total_locales ?></span>
                <div class="text-xs uppercase text-gray-400 font-semibold mt-1 tracking-widest">Locales</div>
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

        <!-- (Opcional) Atajos rápidos -->
        <div class="mb-8 flex flex-wrap gap-4">
            <a href="mis_locales.php" class="flex items-center gap-2 bg-orange-50 text-orange-700 px-4 py-2 rounded-lg hover:bg-orange-100 transition shadow">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 9.75V18a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18V9.75M12 3v8.25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Ver Locales
            </a>
            <a href="editar_carta.php?local_id=<?= $locales[0]['id'] ?? 0 ?>" class="flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-100 transition shadow">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16.862 3.487a2.25 2.25 0 113.182 3.182l-11.07 11.07a4.5 4.5 0 01-1.988 1.164l-3.381.96.96-3.38a4.5 4.5 0 011.164-1.988l11.07-11.07z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Editar Carta
            </a>
            <!-- Más atajos aquí -->
        </div>

        <!-- (Opcional) Área para avisos, novedades o soporte -->
        <div class="bg-yellow-50 border-l-4 border-yellow-300 text-yellow-800 p-4 rounded-xl shadow mb-10">
            <strong class="block mb-1">¿Necesitas ayuda?</strong>
            Puedes contactarnos al <a href="mailto:soporte@qarta.pe" class="underline">soporte@qarta.pe</a> o ver la <a href="#" class="underline">guía de uso</a>.
        </div>

        <!-- (Opcional) Gráfica de ejemplo (puedes poner Chart.js real si lo deseas) -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-semibold text-lg mb-4 text-gray-700">Actividad reciente (Ejemplo)</h2>
            <div class="w-full">
                <img src="https://quickchart.io/chart?c={type:'bar',data:{labels:['Ene','Feb','Mar','Abr','May','Jun'],datasets:[{label:'Platos agregados',data:[12,19,3,5,2,3]}]}}" alt="Actividad reciente" class="w-full max-w-md mx-auto">
            </div>
        </div>

    </div>
</main>

<?php require "../includes/footer.php"; ?>
</body>
</html>
