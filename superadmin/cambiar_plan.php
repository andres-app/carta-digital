<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';

$empresa_id = $_POST['empresa_id'] ?? null;
$nuevo_plan = $_POST['plan'] ?? null;

// Validar inputs
if (
    $empresa_id && is_numeric($empresa_id) &&
    in_array($nuevo_plan, ['gratis', 'basico', 'premium'])
) {
    $stmt = $conn->prepare("UPDATE empresas SET plan = ? WHERE id = ?");
    $stmt->execute([$nuevo_plan, $empresa_id]);
    $success = true;
} else {
    $success = false;
}

// Redirige, mostrando mensaje solo si fue exitoso
header("Location: ver_empresa.php?id=" . urlencode($empresa_id) . ($success ? "&exito=1" : ""));
exit;
