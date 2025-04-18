<?php
require_once '../includes/db.php';

$local_id = $_POST['local_id'] ?? null;

if ($_POST['accion'] === 'crear') {
    $stmt = $conn->prepare("INSERT INTO categorias (local_id, nombre, color) VALUES (?, ?, ?)");
    $stmt->execute([$local_id, $_POST['nombre'], $_POST['color']]);
} elseif ($_POST['accion'] === 'eliminar') {
    // Necesitamos obtener el local_id para redirigir correctamente
    $stmt = $conn->prepare("SELECT local_id FROM categorias WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    $local_id = $stmt->fetchColumn();

    $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}

header("Location: editar_carta.php?local_id=$local_id");
exit;