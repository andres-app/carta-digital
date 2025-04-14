<?php
require_once '../includes/db.php';

$slug = $_GET['slug'] ?? '';

$stmt = $conn->prepare("SELECT * FROM locales WHERE slug = ?");
$stmt->execute([$slug]);
$local = $stmt->fetch();

if (!$local) {
    die("Carta no encontrada");
}

// Obtener categorías
$stmt = $conn->prepare("SELECT * FROM categorias WHERE local_id = ?");
$stmt->execute([$local['id']]);
$categorias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carta - <?= htmlspecialchars($local['nombre']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 font-sans">
    <div class="max-w-3xl mx-auto p-6">
        <!-- Nombre del local -->
        <h1 class="text-3xl font-bold text-center mb-6"><?= htmlspecialchars($local['nombre']) ?></h1>

        <!-- Carta por categorías -->
        <?php foreach ($categorias as $cat): ?>
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-2" style="color: <?= $cat['color'] ?>"><?= htmlspecialchars($cat['nombre']) ?></h2>
                <div class="space-y-4">
                    <?php
                    $stmtPlatos = $conn->prepare("SELECT * FROM platos WHERE categoria_id = ?");
                    $stmtPlatos->execute([$cat['id']]);
                    $platos = $stmtPlatos->fetchAll();
                    foreach ($platos as $plato):
                    ?>
                        <div class="p-4 border rounded-lg shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-bold"><?= htmlspecialchars($plato['nombre']) ?></h3>
                                    <?php if ($plato['descripcion']): ?>
                                        <p class="text-sm text-gray-500"><?= htmlspecialchars($plato['descripcion']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <span class="text-green-600 font-bold text-lg">S/ <?= number_format($plato['precio'], 2) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
