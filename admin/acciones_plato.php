<?php
require_once '../includes/db.php';

if ($_POST['accion'] === 'crear') {
    $stmt = $conn->prepare("INSERT INTO platos (categoria_id, nombre, descripcion, precio) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['categoria_id'],
        $_POST['nombre'],
        $_POST['descripcion'],
        $_POST['precio']
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