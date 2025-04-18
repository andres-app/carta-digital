<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';

$empresa_id = $_POST['empresa_id'];
$nombre = $_POST['nombre'];
$slug = $_POST['slug'];

$stmt = $conn->prepare("INSERT INTO locales (empresa_id, nombre, slug) VALUES (?, ?, ?)");
$stmt->execute([$empresa_id, $nombre, $slug]);

header("Location: ver_empresa.php?id=$empresa_id");
exit;
