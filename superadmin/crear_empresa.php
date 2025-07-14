<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/db.php';

// Validar y limpiar datos
$nombre   = trim($_POST['nombre'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$nombre || !$email || !$password) {
    $_SESSION['mensaje_error'] = "Todos los campos son obligatorios.";
    header("Location: index.php");
    exit;
}

// Verificar si el email ya existe como empresa
$stmt = $conn->prepare("SELECT COUNT(*) FROM empresas WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['mensaje_error'] = "El email ya está registrado como empresa.";
    header("Location: index.php");
    exit;
}

// 1. Crear empresa
$stmt = $conn->prepare("INSERT INTO empresas (nombre, email) VALUES (?, ?)");
$stmt->execute([$nombre, $email]);

// 2. Obtener ID de empresa creada
$empresa_id = $conn->lastInsertId();

// 3. Crear usuario tipo admin vinculado a esa empresa (¡Contraseña encriptada!)
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol, empresa_id) VALUES (?, ?, ?, 'admin', ?)");
$stmt->execute([$nombre, $email, $hashed_password, $empresa_id]);

// 4. Redirigir de vuelta al index con mensaje de éxito
$_SESSION['mensaje_exito'] = "Empresa y usuario admin creados correctamente.";
header("Location: index.php");
exit;
