<?php
require_once '../includes/db.php';

$slug = $_GET['slug'] ?? '';

$stmt = $conn->prepare("SELECT * FROM locales WHERE slug = ?");
$stmt->execute([$slug]);
$local = $stmt->fetch();

if (!$local) {
    die("Carta no encontrada");
}

$stmt = $conn->prepare("SELECT * FROM categorias WHERE local_id = ?");
$stmt->execute([$local['id']]);
$categorias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carta - <?= htmlspecialchars($local['nombre']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans">

    <header class="sticky top-0 bg-white shadow-md z-50 px-6 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-extrabold text-indigo-600"><?= htmlspecialchars($local['nombre']) ?></h1>

            <!-- Ícono de carrito -->
            <a href="../public/ver_carrito.php"
                class="relative text-indigo-600 hover:text-indigo-800 transition text-xl" title="Ver Pedido">
                🛒
                <span id="carrito-contador"
                    class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
            </a>
        </div>
    </header>


    <main class="max-w-7xl mx-auto px-4 py-8">
        <?php foreach ($categorias as $cat): ?>
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6"><?= htmlspecialchars($cat['nombre']) ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $stmtPlatos = $conn->prepare("SELECT * FROM platos WHERE categoria_id = ?");
                    $stmtPlatos->execute([$cat['id']]);
                    $platos = $stmtPlatos->fetchAll();

                    foreach ($platos as $plato):
                        $img_url = isset($plato['imagen']) && !empty($plato['imagen'])
                            ? htmlspecialchars($plato['imagen'])
                            : 'https://via.placeholder.com/400x300.png?text=Producto';
                        ?>
                        <div class="bg-white rounded-2xl overflow-hidden shadow hover:shadow-lg transition relative group">
                            <img src="<?= $img_url ?>" alt="<?= htmlspecialchars($plato['nombre']) ?>"
                                class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-1"><?= htmlspecialchars($plato['nombre']) ?></h3>
                                <?php if (!empty($plato['descripcion'])): ?>
                                    <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($plato['descripcion']) ?></p>
                                <?php endif; ?>
                                <div class="flex flex-col gap-2">
                                    <span class="text-indigo-600 font-bold text-lg">S/
                                        <?= number_format($plato['precio'], 2) ?></span>
                                    <div class="flex items-center justify-between gap-2">
                                        <!-- Controles de cantidad -->
                                        <div class="flex items-center gap-2 border rounded px-2">
                                            <button type="button" class="btn-menos text-xl px-2"
                                                data-id="<?= $plato['id'] ?>">−</button>
                                            <input type="text" id="cantidad-<?= $plato['id'] ?>" value="1" readonly
                                                class="w-8 text-center text-sm">
                                            <button type="button" class="btn-mas text-xl px-2"
                                                data-id="<?= $plato['id'] ?>">+</button>
                                        </div>

                                        <!-- Botón agregar -->
                                        <button
                                            class="btn-agregar bg-indigo-500 text-white px-3 py-1 rounded hover:bg-indigo-600 transition text-sm"
                                            data-id="<?= $plato['id'] ?>"
                                            data-nombre="<?= htmlspecialchars($plato['nombre']) ?>"
                                            data-precio="<?= $plato['precio'] ?>">Agregar</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const contador = document.getElementById("carrito-contador");
            let carrito = JSON.parse(localStorage.getItem("carrito")) || {};
            let total = 0;
            for (let key in carrito) {
                total += carrito[key].cantidad;
            }
            contador.textContent = total;
        });

        document.addEventListener("DOMContentLoaded", () => {
            const contador = document.getElementById("carrito-contador");

            function actualizarContador() {
                const carrito = JSON.parse(localStorage.getItem("carrito")) || {};
                let total = 0;
                for (let key in carrito) {
                    total += carrito[key].cantidad;
                }
                contador.textContent = total;
            }

            actualizarContador(); // Mostrar el número inicial

            // Agregar eventos a los botones +
            const botonesAgregar = document.querySelectorAll(".btn-agregar");
            botonesAgregar.forEach(boton => {
                boton.addEventListener("click", () => {
                    const id = boton.dataset.id;
                    const nombre = boton.dataset.nombre;
                    const precio = parseFloat(boton.dataset.precio);

                    let carrito = JSON.parse(localStorage.getItem("carrito")) || {};

                    if (carrito[id]) {
                        carrito[id].cantidad += 1;
                    } else {
                        carrito[id] = {
                            nombre: nombre,
                            precio: precio,
                            cantidad: 1
                        };
                    }

                    localStorage.setItem("carrito", JSON.stringify(carrito));
                    actualizarContador();
                });
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            // + y - cantidad
            document.querySelectorAll(".btn-mas").forEach(btn => {
                btn.addEventListener("click", () => {
                    const id = btn.dataset.id;
                    const input = document.getElementById("cantidad-" + id);
                    let cantidad = parseInt(input.value);
                    input.value = cantidad + 1;
                });
            });

            document.querySelectorAll(".btn-menos").forEach(btn => {
                btn.addEventListener("click", () => {
                    const id = btn.dataset.id;
                    const input = document.getElementById("cantidad-" + id);
                    let cantidad = parseInt(input.value);
                    if (cantidad > 1) input.value = cantidad - 1;
                });
            });

            // Agregar al carrito
            document.querySelectorAll(".btn-agregar").forEach(btn => {
                btn.addEventListener("click", () => {
                    const id = btn.dataset.id;
                    const nombre = btn.dataset.nombre;
                    const precio = parseFloat(btn.dataset.precio);
                    const cantidad = parseInt(document.getElementById("cantidad-" + id).value);

                    let carrito = JSON.parse(localStorage.getItem("carrito")) || {};

                    if (carrito[id]) {
                        carrito[id].cantidad += cantidad;
                    } else {
                        carrito[id] = {
                            nombre: nombre,
                            precio: precio,
                            cantidad: cantidad
                        };
                    }

                    localStorage.setItem("carrito", JSON.stringify(carrito));

                    // Actualizar contador
                    const contador = document.getElementById("carrito-contador");
                    let total = 0;
                    for (let key in carrito) {
                        total += carrito[key].cantidad;
                    }
                    contador.textContent = total;
                });
            });
        });
    </script>

</body>

</html>