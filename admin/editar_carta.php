<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['empresa_id'])) {
    header("Location: login.php");
    exit;
}

$empresa_id = $_SESSION['empresa_id'];
$local_id = $_GET['local_id'] ?? 0;

// Verifica si el local pertenece a la empresa
$stmt = $conn->prepare("SELECT * FROM locales WHERE id = ? AND empresa_id = ?");
$stmt->execute([$local_id, $empresa_id]);
$local = $stmt->fetch();

if (!$local) {
    die("Acceso no autorizado.");
}

// Obtener categorías del local
$stmt = $conn->prepare("SELECT * FROM categorias WHERE local_id = ?");
$stmt->execute([$local_id]);
$categorias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Carta - <?= htmlspecialchars($local['nombre']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">✏️ Editar Carta de <strong><?= htmlspecialchars($local['nombre']) ?></strong></h1>
        <a href="dashboard.php" class="text-blue-600 text-sm hover:underline">← Volver</a>

        <!-- Formulario nueva categoría -->
        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-2">Agregar nueva categoría</h2>
            <form method="POST" action="acciones_categoria.php" class="flex gap-4">
                <input type="hidden" name="local_id" value="<?= $local_id ?>">
                <input type="text" name="nombre" placeholder="Nombre de la categoría" class="p-2 border rounded w-full" required>
                <input type="color" name="color" class="p-2 border rounded" value="#EF4444">
                <button type="submit" name="accion" value="crear" class="bg-green-600 text-white px-4 py-2 rounded">Agregar</button>
            </form>
        </div>

        <!-- Lista de categorías y platos -->
        <div class="mt-10 space-y-8">
            <?php foreach ($categorias as $cat): ?>
                <div class="border rounded p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xl font-semibold" style="color: <?= $cat['color'] ?>"><?= htmlspecialchars($cat['nombre']) ?></h3>
                        <form method="POST" action="acciones_categoria.php" onsubmit="return confirm('¿Eliminar esta categoría?');">
                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                            <button type="submit" name="accion" value="eliminar" class="text-red-600 hover:underline text-sm">Eliminar</button>
                        </form>
                    </div>

                    <!-- Lista de platos -->
                    <?php
                    $stmtPlatos = $conn->prepare("SELECT * FROM platos WHERE categoria_id = ?");
                    $stmtPlatos->execute([$cat['id']]);
                    $platos = $stmtPlatos->fetchAll();
                    ?>

                    <ul class="space-y-2">
                        <?php foreach ($platos as $plato): ?>
                            <li class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <strong><?= htmlspecialchars($plato['nombre']) ?></strong><br>
                                    <small class="text-gray-500"><?= htmlspecialchars($plato['descripcion']) ?></small>
                                </div>
                                <div class="text-right">
                                    <div>S/ <?= number_format($plato['precio'], 2) ?></div>
                                    <form method="POST" action="acciones_plato.php" class="inline">
                                        <input type="hidden" name="id" value="<?= $plato['id'] ?>">
                                        <button type="submit" name="accion" value="eliminar" class="text-red-600 hover:underline text-sm">Eliminar</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Formulario para agregar plato -->
                    <form method="POST" action="acciones_plato.php" class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-2 items-end">
                        <input type="hidden" name="categoria_id" value="<?= $cat['id'] ?>">
                        <input type="text" name="nombre" placeholder="Nombre del plato" class="p-2 border rounded" required>
                        <input type="text" name="descripcion" placeholder="Descripción" class="p-2 border rounded">
                        <input type="number" step="0.01" name="precio" placeholder="Precio" class="p-2 border rounded" required>
                        <button type="submit" name="accion" value="crear" class="bg-blue-600 text-white px-4 py-2 rounded">Agregar Plato</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
