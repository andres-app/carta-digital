<?php
require_once '../includes/db.php';

if ($_POST['accion'] === 'crear') {
    $stmt = $conn->prepare("INSERT INTO categorias (local_id, nombre, color) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['local_id'], $_POST['nombre'], $_POST['color']]);
} elseif ($_POST['accion'] === 'eliminar') {
    $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}

header("Location: editar_carta.php?local_id=" . ($_POST['local_id'] ?? ''));
exit;
