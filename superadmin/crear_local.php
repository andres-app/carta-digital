<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';

$empresa_id = $_POST['empresa_id'];
$nombre = trim($_POST['nombre']);
$slug = trim($_POST['slug']);

// Validar duplicado de slug
$stmt = $conn->prepare("SELECT COUNT(*) FROM locales WHERE empresa_id = ? AND slug = ?");
$stmt->execute([$empresa_id, $slug]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['mensaje_error'] = "El slug <strong>$slug</strong> ya existe para esta empresa.";
    header("Location: ver_empresa.php?id=$empresa_id");
    exit;
}

// Insertar
$stmt = $conn->prepare("INSERT INTO locales (empresa_id, nombre, slug) VALUES (?, ?, ?)");
$stmt->execute([$empresa_id, $nombre, $slug]);

$_SESSION['mensaje_exito'] = "El local <strong>$nombre</strong> fue creado correctamente.";
header("Location: ver_empresa.php?id=$empresa_id");
exit;
