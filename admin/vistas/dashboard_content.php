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

<div class="max-w-5xl mx-auto">
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

    <!-- Título -->
    <h2 class="text-2xl font-bold text-[#3A1F0F] mb-2">Mis Locales</h2>
        <br>
    <!-- Formulario para agregar local -->
    <?php if ($disponibles > 0): ?>
        <!-- Botón para mostrar/ocultar formulario -->
        <button id="toggle-form-btn" onclick="toggleForm()"
            class="mb-4 bg-[#E94E2C] text-white px-4 py-2 rounded-lg hover:bg-[#cc3f20] transition">
            ➕ Agregar Local
        </button>

        <!-- Formulario oculto inicialmente -->
        <form id="form-agregar-local" method="POST" action="crear_local.php"
            class="hidden mb-8 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <input type="text" name="nombre" placeholder="Nombre del local" required
                class="p-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-[#E94E2C] outline-none">
            <input type="text" name="slug" placeholder="Slug (URL)" required
                class="p-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-[#E94E2C] outline-none">
            <button type="submit"
                class="bg-[#E94E2C] text-white font-semibold px-4 py-2 rounded-lg hover:bg-[#cc3f20] transition">
                Guardar Local
            </button>
        </form>
    <?php endif; ?>

    <!-- Mensaje si no hay locales -->
    <?php if (empty($locales)): ?>
        <p class="text-gray-500 italic">No tienes locales registrados aún.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($locales as $local): ?>
                <div class="bg-white rounded-2xl border border-gray-200 shadow hover:shadow-md transition p-6 flex flex-col justify-between">
                    <!-- Encabezado -->
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold text-[#3A1F0F] leading-snug"><?= htmlspecialchars($local['nombre']) ?></h3>
                        <span class="text-xs bg-[#FFF5E1] text-[#E94E2C] px-2 py-1 rounded-full font-mono border border-[#f5c2a0]">
                            <?= htmlspecialchars($local['slug']) ?>
                        </span>
                    </div>

                    <!-- Acciones -->
                    <div class="flex gap-3 text-sm text-gray-700 mt-2">
                        <!-- QR -->
                        <a href="../admin/generar_qr.php?local_id=<?= $local['id'] ?>" title="Descargar QR"
                            class="flex items-center gap-2 bg-pink-50 text-pink-600 hover:bg-pink-100 px-3 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h3m4 0h3a1 1 0 011 1v3m0 4v3m0 4v3a1 1 0 01-1 1h-3m-4 0H4a1 1 0 01-1-1v-3M4 12V9m0 0V4m0 5h5m0 0v5m0 0H4" />
                            </svg>
                            QR
                        </a>

                        <!-- Editar -->
                        <a href="./editar_carta.php?local_id=<?= $local['id'] ?>" title="Editar carta"
                            class="flex items-center gap-2 bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5-11l5 5m0 0L16 3m5 5L10 18H5v-5L16 3z" />
                            </svg>
                            Editar
                        </a>

                        <!-- Ver -->
                        <a href="<?= $base_url ?>/public/carta.php?slug=<?= urlencode($local['slug']) ?>" target="_blank"
                            title="Ver carta"
                            class="flex items-center gap-2 bg-green-50 text-green-700 hover:bg-green-100 px-3 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ver
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    setTimeout(() => {
        const alerta = document.getElementById("mensaje-alerta");
        if (alerta) {
            alerta.classList.add("opacity-0", "transition", "duration-500");
            setTimeout(() => alerta.remove(), 500); // quitarlo del DOM después de desvanecerse
        }
    }, 4000);

    function toggleForm() {
        const form = document.getElementById("form-agregar-local");
        const button = document.getElementById("toggle-form-btn");
        form.classList.toggle("hidden");
        button.textContent = form.classList.contains("hidden") ? "➕ Agregar Local" : "✖ Cancelar";
    }
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
