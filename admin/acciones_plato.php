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

} elseif ($_POST['accion'] === 'editar') {
    // Recuperar datos
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    // Obtener imagen actual
    $stmt = $conn->prepare("SELECT imagen, categoria_id FROM platos WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    $imagen_actual = $row['imagen'];
    $categoria_id = $row['categoria_id'];

    // Recuperar local_id para redirigir después
    $stmt = $conn->prepare("SELECT local_id FROM categorias WHERE id = ?");
    $stmt->execute([$categoria_id]);
    $local_id = $stmt->fetchColumn();

    // Manejo de imagen nueva
    $ruta_imagen = $imagen_actual; // Por defecto queda igual
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
            // Borra la imagen anterior si existe y no es null
            if ($imagen_actual && file_exists('../' . $imagen_actual)) {
                @unlink('../' . $imagen_actual);
            }
            $ruta_imagen = 'uploads/' . $nombre_final;
        }
    }

    // Actualizar en la BD
    $stmt = $conn->prepare("UPDATE platos SET nombre=?, descripcion=?, precio=?, imagen=? WHERE id=?");
    $stmt->execute([$nombre, $descripcion, $precio, $ruta_imagen, $id]);

} elseif ($_POST['accion'] === 'eliminar') {
    // Obtener local_id antes de eliminar
    $stmt = $conn->prepare("SELECT categoria_id, imagen FROM platos WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    $row = $stmt->fetch();
    $categoria_id = $row['categoria_id'];
    $imagen = $row['imagen'];

    // Borrar imagen física si existe
    if ($imagen && file_exists('../' . $imagen)) {
        @unlink('../' . $imagen);
    }

    $stmt = $conn->prepare("SELECT local_id FROM categorias WHERE id = ?");
    $stmt->execute([$categoria_id]);
    $local_id = $stmt->fetchColumn();

    $stmt = $conn->prepare("DELETE FROM platos WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}

// Redirección siempre al final
header("Location: editar_carta.php?local_id=$local_id");
exit;
