<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['empresa_id'])) {
    header("Location: login.php");
    exit;
}

$empresa_id = $_SESSION['empresa_id'];

$stmt = $conn->prepare("SELECT * FROM locales WHERE empresa_id = ?");
$stmt->execute([$empresa_id]);
$locales = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Locales</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Hola, <?= htmlspecialchars($_SESSION['empresa_nombre']) ?></h1>
            <a href="logout.php" class="text-sm text-red-600 hover:underline">Cerrar sesión</a>
        </div>

        <h2 class="text-xl font-semibold mb-4">Mis Locales</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($locales as $local): ?>
                <div class="p-4 border rounded shadow hover:shadow-md">
                    <h3 class="font-semibold text-lg"><?= htmlspecialchars($local['nombre']) ?></h3>
                    <p class="text-sm text-gray-600">Slug: <?= htmlspecialchars($local['slug']) ?></p>
                    <div class="mt-3 flex justify-between items-center">
                        <a href="editar_carta.php?local_id=<?= $local['id'] ?>" class="text-blue-600 hover:underline">Editar carta</a>
                        <a href="../public/carta.php?slug=<?= urlencode($local['slug']) ?>" target="_blank" class="text-green-600 hover:underline">Ver carta</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
