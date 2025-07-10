<?php
session_start();
require "../includes/header.php";
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
    <title>Editar Carta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<?php require "../includes/header.php"; ?>

<main class="ml-60 min-h-screen px-2 py-8">
    <div class="max-w-5xl mx-auto bg-white p-4 md:p-8 rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <span class="text-orange-600">✏️</span>
            Editar Carta de <strong class="ml-2"><?= htmlspecialchars($local['nombre']) ?></strong>
        </h1>
        <a href="mis_locales.php" class="text-blue-600 text-sm hover:underline mb-4 inline-block">← Volver</a>

        <!-- Formulario nueva categoría -->
        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-2">Agregar nueva categoría</h2>
            <form method="POST" action="acciones_categoria.php" class="flex flex-col md:flex-row gap-2 md:gap-4 items-center">
                <input type="hidden" name="local_id" value="<?= $local_id ?>">
                <input type="text" name="nombre" placeholder="Nombre de la categoría" class="p-2 border rounded w-full md:w-auto flex-1" required>
                <div class="flex items-center gap-2">
                    <label for="color" class="text-sm">Color:</label>
                    <input type="color" id="color" name="color" class="border rounded w-10 h-10 cursor-pointer" value="#EF4444" title="Elige un color">
                </div>
                <button type="submit" name="accion" value="crear" class="bg-green-600 text-white px-4 py-2 rounded w-full md:w-auto">Agregar</button>
            </form>
        </div>

        <!-- Lista de categorías y platos -->
        <div class="mt-10 space-y-8">
            <?php foreach ($categorias as $cat): ?>
                <div class="border rounded-lg p-4">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-2 gap-2">
                        <h3 class="text-xl font-semibold" style="color: <?= $cat['color'] ?>"><?= htmlspecialchars($cat['nombre']) ?></h3>
                        <form method="POST" action="acciones_categoria.php" onsubmit="return confirm('¿Eliminar esta categoría?');">
                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
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
                            <li class="flex flex-col md:flex-row md:justify-between md:items-center border-b pb-2 gap-2">
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
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- Botón flotante responsive para agregar plato -->
<button
    type="button"
    id="btnAgregarPlatoGlobal"
    class="fixed bottom-5 right-5 z-50 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg w-14 h-14 md:w-16 md:h-16 flex items-center justify-center text-3xl transition-all duration-200"
    title="Agregar plato"
    style="box-shadow: 0 4px 24px rgba(59,130,246,0.25);">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 md:w-10 md:h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>
</button>

<!-- Modal Formulario responsive y compacto -->
<div id="modalAgregarPlato"
    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg px-4 py-6 md:px-8 md:py-8 relative flex flex-col justify-center max-h-[90vh] overflow-y-auto">
        <button type="button" id="cerrarModalPlato"
            class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-2xl md:text-xl">&times;</button>
        <h2 class="text-lg font-semibold mb-4">Agregar nuevo plato</h2>
        <form method="POST" action="acciones_plato.php" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block mb-1 text-sm">Categoría:</label>
                <select name="categoria_id" required class="border rounded w-full p-2">
                    <option value="" disabled selected>Selecciona una categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <input type="text" name="nombre" placeholder="Nombre del plato" class="border rounded w-full p-2" required>
            </div>
            <div>
                <input type="text" name="descripcion" placeholder="Descripción" class="border rounded w-full p-2">
            </div>
            <div>
                <input type="number" step="0.01" name="precio" placeholder="Precio" class="border rounded w-full p-2" required>
            </div>
            <div>
                <input type="file" name="imagen" accept="image/*" class="block w-full text-sm" />
            </div>
            <div class="flex justify-end">
                <button type="submit" name="accion" value="crear"
                    class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition hover:bg-blue-100">
                    Agregar Plato
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para controlar el modal de agregar plato -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('btnAgregarPlatoGlobal');
        const modal = document.getElementById('modalAgregarPlato');
        const cerrar = document.getElementById('cerrarModalPlato');

        btn.addEventListener('click', () => modal.classList.remove('hidden'));
        cerrar.addEventListener('click', () => modal.classList.add('hidden'));

        // Cerrar modal al hacer click fuera del contenido
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.classList.add('hidden');
        });
    });
</script>

<?php require "../includes/footer.php"; ?>

</body>
</html>
