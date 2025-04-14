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
} elseif ($_POST['accion'] === 'eliminar') {
    $stmt = $conn->prepare("DELETE FROM platos WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}

header("Location: editar_carta.php?local_id=" . ($_POST['local_id'] ?? $_GET['local_id']));
exit;
