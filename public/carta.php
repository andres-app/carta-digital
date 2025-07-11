<?php
require_once '../includes/db.php';

$slug = $_GET['slug'] ?? '';

$stmt = $conn->prepare("SELECT * FROM locales WHERE slug = ?");
$stmt->execute([$slug]);
$local = $stmt->fetch();

if (!$local) {
    die("Carta no encontrada");
}

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
</head>

<body class="bg-white text-gray-800 font-sans">

    <!-- Encabezado Fijo -->
    <header class="sticky top-0 bg-white shadow-md z-50 p-4 text-center">
        <h1 class="text-2xl font-extrabold text-indigo-600"><?= htmlspecialchars($local['nombre']) ?></h1>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <?php foreach ($categorias as $cat): ?>
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6"><?= htmlspecialchars($cat['nombre']) ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $stmtPlatos = $conn->prepare("SELECT * FROM platos WHERE categoria_id = ?");
                    $stmtPlatos->execute([$cat['id']]);
                    $platos = $stmtPlatos->fetchAll();

                    foreach ($platos as $plato):
                        $img_url = (isset($plato['imagen']) && !empty($plato['imagen']))
                            ? '/' . ltrim($plato['imagen'], '/')
                            : 'https://via.placeholder.com/400x300.png?text=Producto';

                    ?>
                        <div class="bg-white rounded-2xl overflow-hidden shadow hover:shadow-lg transition relative group">
                            <img src="/carta-digital/<?= htmlspecialchars($plato['imagen']) ?>" alt="<?= htmlspecialchars($plato['nombre']) ?>" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-1"><?= htmlspecialchars($plato['nombre']) ?></h3>
                                <?php if (!empty($plato['descripcion'])): ?>
                                    <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($plato['descripcion']) ?></p>
                                <?php endif; ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-indigo-600 font-bold text-lg">S/ <?= number_format($plato['precio'], 2) ?></span>
                                    <button class="bg-indigo-500 text-white rounded-full w-9 h-9 flex items-center justify-center text-2xl leading-none hover:bg-indigo-600 transition shadow-md">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>
</body>

</html>