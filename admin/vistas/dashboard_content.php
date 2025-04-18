<?php
require_once __DIR__ . '/../../includes/db.php';

$empresa_id = $_SESSION['empresa_id'];
$stmt = $conn->prepare("SELECT * FROM locales WHERE empresa_id = ?");
$stmt->execute([$empresa_id]);
$locales = $stmt->fetchAll();
?>

<h2 class="text-xl font-semibold mb-4">Mis Locales</h2>

<?php if (empty($locales)): ?>
    <p class="text-gray-600">No tienes locales registrados aún.</p>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php foreach ($locales as $local): ?>
            <div class="p-4 border rounded shadow hover:shadow-md">
                <h3 class="font-semibold text-lg"><?= htmlspecialchars($local['nombre']) ?></h3>
                <p class="text-sm text-gray-600">Slug: <?= htmlspecialchars($local['slug']) ?></p>
                <div class="mt-3 flex justify-between items-center">
                    <a href="../editar_carta.php?local_id=<?= $local['id'] ?>" class="text-blue-600 hover:underline">Editar carta</a>
                    <a href="/carta-digital/public/carta.php?slug=<?= urlencode($local['slug']) ?>" target="_blank" class="text-green-600 hover:underline">Ver carta</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
