<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'TuCarta Panel' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-[#3A1F0F]">

    <!-- Header fijo, fuera del contenedor -->
    <header class="sticky top-0 left-0 w-full bg-white shadow z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <?php include 'header.php'; ?>
        </div>
    </header>

    <!-- Espacio para no pegar contenido al header -->
    <div class="h-4"></div>

    <!-- Contenido principal con caja blanca -->
    <main class="max-w-6xl mx-auto px-4 pb-24">
        <div class="bg-white rounded-xl shadow p-6">
            <?php include $contenido; ?>
        </div>
    </main>



</body>
</html>
