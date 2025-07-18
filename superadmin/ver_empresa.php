<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';
require_once '../includes/header.php';

// Validar el ID de empresa recibido por GET
$empresa_id = $_GET['id'] ?? null;
if (!$empresa_id) {
    die("ID de empresa no válido.");
}

// Obtener datos de la empresa
$stmt = $conn->prepare("SELECT * FROM empresas WHERE id = ?");
$stmt->execute([$empresa_id]);
$empresa = $stmt->fetch();

if (!$empresa) {
    die("Empresa no encontrada.");
}

// Obtener locales
$stmt = $conn->prepare("SELECT * FROM locales WHERE empresa_id = ?");
$stmt->execute([$empresa_id]);
$locales = $stmt->fetchAll();
?>

<div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow-md mt-8">
    <a href="index.php" class="text-sm text-blue-600 hover:underline">← Volver</a>
    <h1 class="text-2xl font-bold mt-2 mb-6">Locales de <?= htmlspecialchars($empresa['nombre']) ?></h1>

    <?php if (isset($_GET['exito'])): ?>
        <p class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-sm border border-green-200">
            ✅ Plan actualizado correctamente.
        </p>
    <?php endif; ?>

    <!-- Cambiar plan -->
    <form action="cambiar_plan.php" method="POST" class="mb-8">
        <input type="hidden" name="empresa_id" value="<?= $empresa['id'] ?>">
        <label class="block mb-2 font-medium text-sm">Plan actual de la empresa</label>
        <div class="flex gap-4 items-center">
            <select name="plan" class="border rounded p-2">
                <option value="gratis" <?= $empresa['plan'] === 'gratis' ? 'selected' : '' ?>>Gratis (1 local)</option>
                <option value="basico" <?= $empresa['plan'] === 'basico' ? 'selected' : '' ?>>Básico (3 locales)</option>
                <option value="premium" <?= $empresa['plan'] === 'premium' ? 'selected' : '' ?>>Premium (Ilimitado)</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Actualizar plan</button>
        </div>
    </form>

    <?php if (!empty($_SESSION['mensaje_error'])): ?>
        <div id="mensaje-alerta" class="bg-red-100 text-red-700 px-4 py-2 rounded mb-6 text-sm shadow">
            <?= $_SESSION['mensaje_error'];
            unset($_SESSION['mensaje_error']); ?>
        </div>
    <?php elseif (!empty($_SESSION['mensaje_exito'])): ?>
        <div id="mensaje-alerta" class="bg-green-100 text-green-700 px-4 py-2 rounded mb-6 text-sm shadow">
            <?= $_SESSION['mensaje_exito'];
            unset($_SESSION['mensaje_exito']); ?>
        </div>
    <?php endif; ?>

    <!-- Crear nuevo local -->
    <form method="POST" action="crear_local.php" class="mb-8 flex gap-4 items-end">
        <input type="hidden" name="empresa_id" value="<?= $empresa['id'] ?>">
        <div class="flex-1">
            <label class="block mb-1">Nombre del Local</label>
            <input type="text" name="nombre" id="nombre_local" class="w-full p-2 border rounded" required>
        </div>
        <div class="flex-1">
            <label class="block mb-1">Slug (URL corta)</label>
            <input type="text" name="slug" id="slug_local" class="w-full p-2 border rounded bg-gray-50" readonly required>
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
                        <a href="../public/carta.php?slug=<?= urlencode($local['slug']) ?>"
                            class="text-blue-600 hover:underline" target="_blank">Ver Carta</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    setTimeout(() => {
        const alerta = document.getElementById("mensaje-alerta");
        if (alerta) {
            alerta.classList.add("opacity-0", "transition", "duration-500");
            setTimeout(() => alerta.remove(), 500); // quitarlo del DOM después de desvanecerse
        }
    }, 4000);

    function stringToSlug(str) {
        return str
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // quita acentos
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-') // reemplaza no-alfanumérico por guion
            .replace(/^-+|-+$/g, '') // quita guiones al inicio/fin
            .replace(/--+/g, '-'); // evita guiones dobles
    }

    document.getElementById('nombre_local').addEventListener('input', function() {
        document.getElementById('slug_local').value = stringToSlug(this.value);
    });
</script>

<?php require_once '../includes/footer.php'; ?>