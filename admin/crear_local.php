<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';
require_once '../includes/helpers.php';

$empresa_id = $_SESSION['empresa_id'];
$nombre = trim($_POST['nombre']);
$slug = trim($_POST['slug']);

// Validar duplicado de slug
$stmt = $conn->prepare("SELECT COUNT(*) FROM locales WHERE empresa_id = ? AND slug = ?");
$stmt->execute([$empresa_id, $slug]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['mensaje_error'] = "El slug <strong>$slug</strong> ya existe en tus locales. Usa uno diferente.";
    header("Location: mis_locales.php");
    exit;
}

// Validar límite de locales
$stmt = $conn->prepare("SELECT plan FROM empresas WHERE id = ?");
$stmt->execute([$empresa_id]);
$plan = $stmt->fetchColumn();
$limite = obtenerLimiteLocalesPorPlan($plan);

$stmt = $conn->prepare("SELECT COUNT(*) FROM locales WHERE empresa_id = ?");
$stmt->execute([$empresa_id]);
$total_locales = $stmt->fetchColumn();

if ($total_locales >= $limite) {
    $_SESSION['mensaje_error'] = "Has alcanzado el límite de locales para el plan <strong>$plan</strong>.";
    header("Location: mis_locales.php");
    exit;
}

// Insertar
$stmt = $conn->prepare("INSERT INTO locales (empresa_id, nombre, slug) VALUES (?, ?, ?)");
$stmt->execute([$empresa_id, $nombre, $slug]);

$_SESSION['mensaje_exito'] = "El local <strong>$nombre</strong> fue creado exitosamente.";
header("Location: mis_locales.php");
exit;
