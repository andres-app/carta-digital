<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';

// Validaciones básicas
$empresa_id = $_POST['empresa_id'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$slug = trim(strtolower($_POST['slug'] ?? ''));

if (!$empresa_id || !is_numeric($empresa_id) || !$nombre || !$slug) {
    $_SESSION['mensaje_error'] = "Datos incompletos para crear el local.";
    header("Location: ver_empresa.php?id=" . urlencode($empresa_id));
    exit;
}

// Sanitiza el nombre (el slug lo puedes filtrar para solo permitir a-z, 0-9 y guion si quieres)
$nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
$slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

// Validar duplicado de slug para esta empresa
$stmt = $conn->prepare("SELECT COUNT(*) FROM locales WHERE empresa_id = ? AND slug = ?");
$stmt->execute([$empresa_id, $slug]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['mensaje_error'] = "El slug <strong>$slug</strong> ya existe para esta empresa.";
    header("Location: ver_empresa.php?id=" . urlencode($empresa_id));
    exit;
}

// Insertar local
$stmt = $conn->prepare("INSERT INTO locales (empresa_id, nombre, slug) VALUES (?, ?, ?)");
$stmt->execute([$empresa_id, $nombre, $slug]);

$_SESSION['mensaje_exito'] = "El local <strong>$nombre</strong> fue creado correctamente.";
header("Location: ver_empresa.php?id=" . urlencode($empresa_id));
exit;
