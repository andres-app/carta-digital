<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'TuCarta Panel' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-[#3A1F0F]">

<?php include 'header.php'; ?>

<div class="flex">
    <?php include 'sidebar.php'; ?>

    <main class="flex-1 px-4 py-10 transition-all duration-300 md:ml-60">
        <div class="bg-white rounded-xl shadow p-6 max-w-6xl mx-auto">
            <?php include $contenido; ?>
        </div>
    </main>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
