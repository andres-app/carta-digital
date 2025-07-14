<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';
require_once '../includes/header.php'; // Esto ya incluye head, body, Tailwind y sidebar
?>

<div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-md mt-8">
    <h1 class="text-2xl font-extrabold mb-8 text-blue-700">Panel Superadmin</h1>

    <!-- Crear empresa -->
    <div class="mb-10 bg-blue-50 p-6 rounded-xl shadow flex flex-col gap-4">
        <h2 class="text-xl font-semibold text-blue-700 mb-2">Agregar nueva empresa</h2>
        <form method="POST" action="crear_empresa.php" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block mb-1 font-medium">Nombre de la Empresa</label>
                <input type="text" name="nombre" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-200" required>
            </div>
            <div class="flex-1">
                <label class="block mb-1 font-medium">Correo del Cliente</label>
                <input type="email" name="email" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-200" required>
            </div>
            <div class="flex-1">
                <label class="block mb-1 font-medium">Contraseña</label>
                <input type="password" name="password" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-200" required>
            </div>
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold shadow-md transition">Agregar</button>
        </form>
    </div>

    <!-- Lista de empresas -->
    <div>
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Empresas Registradas</h2>
        <div class="overflow-x-auto rounded-xl shadow">
            <table class="min-w-full table-auto bg-white">
                <thead class="bg-blue-100 text-blue-900">
                    <tr>
                        <th class="text-left px-4 py-2 font-medium">Empresa</th>
                        <th class="text-left px-4 py-2 font-medium">Correo</th>
                        <th class="text-left px-4 py-2 font-medium">Locales</th>
                        <th class="text-left px-4 py-2 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT * FROM empresas ORDER BY nombre");
                    while ($empresa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $locales = $conn->prepare("SELECT COUNT(*) FROM locales WHERE empresa_id = ?");
                        $locales->execute([$empresa['id']]);
                        $num_locales = $locales->fetchColumn();
                        echo "<tr class='border-b hover:bg-blue-50 transition'>
                                <td class='px-4 py-2'>{$empresa['nombre']}</td>
                                <td class='px-4 py-2'>{$empresa['email']}</td>
                                <td class='px-4 py-2'>$num_locales</td>
                                <td class='px-4 py-2'>
                                    <a href='ver_empresa.php?id={$empresa['id']}'
                                        class='text-blue-600 hover:text-blue-900 hover:underline transition font-semibold'>
                                        Ver
                                    </a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
