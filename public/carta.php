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
    <!-- TailwindCSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-white text-gray-800 font-sans">

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
                        $img_src = trim($plato['imagen'] ?? '');
                        $uploads_path = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) ? '/carta-digital/uploads/' : '/uploads/';

                        if (empty($img_src)) {
                            $img_url = 'https://via.placeholder.com/400x300.png?text=Producto';
                        } elseif (preg_match('/^https?:\/\//i', $img_src)) {
                            $img_url = $img_src;
                        } else {
                            // Quita "uploads/" del inicio si lo trae
                            $img_src = preg_replace('#^/?uploads/#i', '', $img_src);
                            $img_url = $uploads_path . htmlspecialchars($img_src);
                        }
                    ?>
                        <div class="bg-white rounded-2xl overflow-hidden shadow hover:shadow-lg transition relative group">
                            <!-- DEBUG: Descomenta para ver la URL de imagen generada -->
                            <!--
                            <div class="mb-1 text-xs text-orange-700 bg-yellow-100 px-2 py-1 rounded">
                                <b>Debug imagen:</b>
                                <a href="<?= $img_url ?>" target="_blank"><?= $img_url ?></a>
                            </div>
                            -->
                            <img src="<?= $img_url ?>" alt="<?= htmlspecialchars($plato['nombre']) ?>" class="w-full h-48 object-cover">
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
