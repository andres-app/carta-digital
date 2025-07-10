<?php
require_once '../includes/db.php';

if ($_POST['accion'] === 'crear') {

    // Manejo de imagen
    $ruta_imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $directorio = '../uploads/';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $nombre_tmp = $_FILES['imagen']['tmp_name'];
        $nombre_original = basename($_FILES['imagen']['name']);
        $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
        $nombre_final = uniqid('plato_', true) . '.' . $extension;

        $ruta_destino = $directorio . $nombre_final;

        if (move_uploaded_file($nombre_tmp, $ruta_destino)) {
            // Guardamos la ruta relativa (para usarla en el HTML)
            $ruta_imagen = 'uploads/' . $nombre_final;
        }
    }

    // Insertar en la BD incluyendo la imagen si fue cargada
    $stmt = $conn->prepare("INSERT INTO platos (categoria_id, nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['categoria_id'],
        $_POST['nombre'],
        $_POST['descripcion'],
        $_POST['precio'],
        $ruta_imagen
    ]);

    // Recuperar local_id para redirigir
    $stmt = $conn->prepare("SELECT local_id FROM categorias WHERE id = ?");
    $stmt->execute([$_POST['categoria_id']]);
    $local_id = $stmt->fetchColumn();

} elseif ($_POST['accion'] === 'eliminar') {
    // Obtener local_id antes de eliminar
    $stmt = $conn->prepare("SELECT categoria_id FROM platos WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    $categoria_id = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT local_id FROM categorias WHERE id = ?");
    $stmt->execute([$categoria_id]);
    $local_id = $stmt->fetchColumn();

    $stmt = $conn->prepare("DELETE FROM platos WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}

header("Location: editar_carta.php?local_id=$local_id");
exit;