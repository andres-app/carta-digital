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
<body class="bg-gradient-to-b from-white to-gray-100 min-h-screen text-gray-800 font-sans">
    <!-- Sticky header -->
    <header class="sticky top-0 z-50 bg-white shadow-sm p-4">
        <h1 class="text-2xl font-bold text-center text-indigo-600"><?= htmlspecialchars($local['nombre']) ?></h1>
    </header>

    <main class="max-w-4xl mx-auto p-6 pt-8">
        <!-- Carta por categorías -->
        <?php foreach ($categorias as $cat): ?>
            <div class="mb-10">
                <h2 class="text-2xl font-extrabold text-gray-700 border-b-2 border-indigo-400 pb-2 mb-6"><?= htmlspecialchars($cat['nombre']) ?></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    $stmtPlatos = $conn->prepare("SELECT * FROM platos WHERE categoria_id = ?");
                    $stmtPlatos->execute([$cat['id']]);
                    $platos = $stmtPlatos->fetchAll();
                    foreach ($platos as $plato):
                    ?>
                        <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
                            <div class="flex flex-col justify-between h-full">
                                <div>
                                    <h3 class="text-lg font-bold mb-2 text-gray-900"><?= htmlspecialchars($plato['nombre']) ?></h3>
                                    <?php if ($plato['descripcion']): ?>
                                        <p class="text-sm text-gray-600 mb-4"><?= htmlspecialchars($plato['descripcion']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex justify-end">
                                    <span class="text-green-600 font-bold text-lg">S/ <?= number_format($plato['precio'], 2) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>
