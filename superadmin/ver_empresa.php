<!-- superadmin/ver_empresa.php -->
<?php
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;

// Obtener datos de la empresa
$stmt = $conn->prepare("SELECT * FROM empresas WHERE id = ?");
$stmt->execute([$id]);
$empresa = $stmt->fetch();

if (!$empresa) {
    die("Empresa no encontrada");
}

// Obtener locales
$stmtLocales = $conn->prepare("SELECT * FROM locales WHERE empresa_id = ?");
$stmtLocales->execute([$id]);
$locales = $stmtLocales->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Locales - <?= htmlspecialchars($empresa['nombre']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow-md">
        <a href="index.php" class="text-sm text-blue-600 hover:underline">← Volver</a>
        <h1 class="text-2xl font-bold mt-2 mb-6">Locales de <?= htmlspecialchars($empresa['nombre']) ?></h1>

        <!-- Crear nuevo local -->
        <form method="POST" action="crear_local.php" class="mb-8 flex gap-4 items-end">
            <input type="hidden" name="empresa_id" value="<?= $empresa['id'] ?>">
            <div class="flex-1">
                <label class="block mb-1">Nombre del Local</label>
                <input type="text" name="nombre" class="w-full p-2 border rounded" required>
            </div>
            <div class="flex-1">
                <label class="block mb-1">Slug (URL corta)</label>
                <input type="text" name="slug" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Agregar Local</button>
        </form>

        <!-- Lista de locales -->
        <table class="w-full table-auto bg-white shadow-sm border rounded">
            <thead class="bg-gray-200">
                <tr>
                    <th class="text-left px-4 py-2">Nombre</th>
                    <th class="text-left px-4 py-2">Slug</th>
                    <th class="text-left px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locales as $local): ?>
                    <tr>
                        <td class="px-4 py-2"><?= htmlspecialchars($local['nombre']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($local['slug']) ?></td>
                        <td class="px-4 py-2">
                            <a href="../public/carta.php?slug=<?= urlencode($local['slug']) ?>" class="text-blue-600 hover:underline" target="_blank">Ver Carta</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
