<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';
require_once '../includes/helpers.php';

$empresa_id = $_SESSION['empresa_id'];
$nombre = $_POST['nombre'];
$slug = $_POST['slug'];

// 1. Obtener plan de la empresa
$stmt = $conn->prepare("SELECT plan FROM empresas WHERE id = ?");
$stmt->execute([$empresa_id]);
$plan = $stmt->fetchColumn();

// 2. Verificar cuántos locales tiene
$stmt = $conn->prepare("SELECT COUNT(*) FROM locales WHERE empresa_id = ?");
$stmt->execute([$empresa_id]);
$total_locales = $stmt->fetchColumn();

// 3. Validar límite
$limite = obtenerLimiteLocalesPorPlan($plan);

if ($total_locales >= $limite) {
    die("Has alcanzado el límite de locales para tu plan ($plan).");
}

// 4. Insertar nuevo local
$stmt = $conn->prepare("INSERT INTO locales (empresa_id, nombre, slug) VALUES (?, ?, ?)");
$stmt->execute([$empresa_id, $nombre, $slug]);

header("Location: dashboard.php");
exit;
