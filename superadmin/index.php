<!-- superadmin/index.php -->
<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Superadmin - Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-md">
        <?php include '../includes/header.php'; ?>



        <!-- Crear empresa -->
        <div class="mb-8">
            <form method="POST" action="crear_empresa.php" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block mb-1">Nombre de la Empresa</label>
                    <input type="text" name="nombre" class="w-full p-2 border rounded" required>
                </div>
                <div class="flex-1">
                    <label class="block mb-1">Correo del Cliente</label>
                    <input type="email" name="email" class="w-full p-2 border rounded" required>
                </div>
                <div class="flex-1">
                    <label class="block mb-1">Contraseña</label>
                    <input type="password" name="password" class="w-full p-2 border rounded" required>
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Agregar</button>
            </form>
        </div>

        <!-- Lista de empresas -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Empresas Registradas</h2>
            <table class="w-full table-auto bg-white shadow-sm border rounded">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="text-left px-4 py-2">Empresa</th>
                        <th class="text-left px-4 py-2">Correo</th>
                        <th class="text-left px-4 py-2">Locales</th>
                        <th class="text-left px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT * FROM empresas");
                    while ($empresa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $locales = $conn->prepare("SELECT COUNT(*) FROM locales WHERE empresa_id = ?");
                        $locales->execute([$empresa['id']]);
                        $num_locales = $locales->fetchColumn();
                        echo "<tr>
                                <td class='px-4 py-2'>{$empresa['nombre']}</td>
                                <td class='px-4 py-2'>{$empresa['email']}</td>
                                <td class='px-4 py-2'>$num_locales</td>
                                <td class='px-4 py-2'>
                                    <a href='ver_empresa.php?id={$empresa['id']}' class='text-blue-600 hover:underline'>Ver</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>