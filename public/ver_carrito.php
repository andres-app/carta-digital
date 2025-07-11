<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <header class="bg-white shadow-md sticky top-0 z-50 px-6 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600">🛒 Mi Pedido</h1>
            <a href="javascript:history.back()" class="text-sm text-indigo-600 hover:underline">← Seguir comprando</a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-8">
        <div id="carrito-container"></div>

        <div class="mt-6 text-right">
            <h2 class="text-xl font-bold">Total: S/ <span id="total-pedido">0.00</span></h2>
            <button id="btn-finalizar" class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Finalizar Pedido
            </button>
        </div>
    </main>

    <script>
        function renderCarrito() {
            const container = document.getElementById("carrito-container");
            const totalSpan = document.getElementById("total-pedido");
            let carrito = JSON.parse(localStorage.getItem("carrito")) || {};
            container.innerHTML = "";
            let total = 0;

            const keys = Object.keys(carrito);
            if (keys.length === 0) {
                container.innerHTML = `<p class="text-center text-gray-500">Tu carrito está vacío.</p>`;
                document.getElementById("btn-finalizar").style.display = "none";
                totalSpan.textContent = "0.00";
                return;
            }

            keys.forEach(id => {
                const item = carrito[id];
                const subtotal = item.precio * item.cantidad;
                total += subtotal;

                container.innerHTML += `
                <div class="bg-white shadow rounded-lg mb-4 p-4">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <h3 class="font-bold text-lg">${item.nombre}</h3>
                            <p class="text-sm text-gray-600">Precio: S/ ${item.precio.toFixed(2)}</p>
                        </div>
                        <button onclick="eliminarProducto('${id}')" class="text-sm text-red-600 hover:underline">Eliminar</button>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2 border rounded px-2">
                            <button class="btn-menos text-xl px-2" data-id="${id}">−</button>
                            <input type="text" id="cantidad-${id}" value="${item.cantidad}" readonly class="w-8 text-center text-sm">
                            <button class="btn-mas text-xl px-2" data-id="${id}">+</button>
                        </div>
                        <div class="text-right font-semibold text-indigo-600">
                            Subtotal: S/ <span id="subtotal-${id}">${subtotal.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
                `;
            });

            totalSpan.textContent = total.toFixed(2);
            document.getElementById("btn-finalizar").style.display = "inline-block";

            // Asignar eventos a los nuevos botones
            document.querySelectorAll(".btn-mas").forEach(btn => {
                btn.addEventListener("click", () => cambiarCantidad(btn.dataset.id, 1));
            });
            document.querySelectorAll(".btn-menos").forEach(btn => {
                btn.addEventListener("click", () => cambiarCantidad(btn.dataset.id, -1));
            });
        }

        function cambiarCantidad(id, delta) {
            let carrito = JSON.parse(localStorage.getItem("carrito")) || {};
            if (!carrito[id]) return;

            carrito[id].cantidad += delta;
            if (carrito[id].cantidad < 1) carrito[id].cantidad = 1;

            localStorage.setItem("carrito", JSON.stringify(carrito));
            renderCarrito();
        }

        function eliminarProducto(id) {
            let carrito = JSON.parse(localStorage.getItem("carrito")) || {};
            delete carrito[id];
            localStorage.setItem("carrito", JSON.stringify(carrito));
            renderCarrito();
        }

        document.getElementById("btn-finalizar").addEventListener("click", () => {
            alert("¡Gracias por tu pedido! Se ha enviado correctamente.");
            localStorage.removeItem("carrito");
            renderCarrito();
        });

        document.addEventListener("DOMContentLoaded", renderCarrito);
    </script>

</body>
</html>
