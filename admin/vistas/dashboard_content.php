<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

$empresa_id = $_SESSION['empresa_id'];

// Obtener locales
$stmt = $conn->prepare("SELECT * FROM locales WHERE empresa_id = ?");
$stmt->execute([$empresa_id]);
$locales = $stmt->fetchAll();
$total_locales = count($locales);

// Obtener plan
$stmt = $conn->prepare("SELECT plan FROM empresas WHERE id = ?");
$stmt->execute([$empresa_id]);
$plan = $stmt->fetchColumn();
$limite = obtenerLimiteLocalesPorPlan($plan);
$disponibles = $limite - $total_locales;
?>

<h2 class="text-xl font-semibold mb-4">Mis Locales</h2>

<p class="text-sm text-gray-500 mb-4">
    Plan actual: <strong><?= ucfirst($plan) ?></strong> — Locales usados: <strong><?= $total_locales ?>/<?= $limite ?></strong>
</p>

<?php if ($disponibles > 0): ?>
    <form method="POST" action="crear_local.php" class="mb-6 flex gap-4 flex-wrap">
        <input type="text" name="nombre" placeholder="Nombre del local" required class="p-2 border rounded w-full md:w-1/3">
        <input type="text" name="slug" placeholder="Slug (URL)" required class="p-2 border rounded w-full md:w-1/3">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Agregar Local</button>
    </form>
<?php else: ?>
    <p class="text-red-600 text-sm font-semibold mb-4">Has alcanzado el límite de locales según tu plan.</p>
<?php endif; ?>

<?php if (empty($locales)): ?>
    <p class="text-gray-600">No tienes locales registrados aún.</p>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php foreach ($locales as $local): ?>
            <div class="p-4 border rounded shadow hover:shadow-md">
                <h3 class="font-semibold text-lg"><?= htmlspecialchars($local['nombre']) ?></h3>
                <p class="text-sm text-gray-600">Slug: <?= htmlspecialchars($local['slug']) ?></p>
                <div class="mt-3 flex justify-between items-center">
                    <a href="./editar_carta.php?local_id=<?= $local['id'] ?>" class="text-blue-600 hover:underline">Editar carta</a>
                    <a href="/carta-digital/public/carta.php?slug=<?= urlencode($local['slug']) ?>" target="_blank" class="text-green-600 hover:underline">Ver carta</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
