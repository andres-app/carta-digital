<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password = $_POST['password']; // Sin encriptar

// 1. Crear empresa
$stmt = $conn->prepare("INSERT INTO empresas (nombre, email) VALUES (?, ?)");
$stmt->execute([$nombre, $email]);

// 2. Obtener ID de empresa creada
$empresa_id = $conn->lastInsertId();

// 3. Crear usuario tipo admin vinculado a esa empresa
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol, empresa_id) VALUES (?, ?, ?, 'admin', ?)");
$stmt->execute([$nombre, $email, $password, $empresa_id]);

// 4. Redirigir de vuelta al index
header("Location: index.php");
exit;
