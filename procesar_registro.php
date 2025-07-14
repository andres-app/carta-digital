<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$nombre || !$email || !$password) {
    $_SESSION['registro_error'] = "Todos los campos son obligatorios.";
    header("Location: registro.php");
    exit;
}

// Verifica si el email ya existe en empresa o usuario
$stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['registro_error'] = "El correo ya está registrado.";
    header("Location: registro.php");
    exit;
}

// 1. Crear empresa con el correo del usuario (plan básico)
$stmt = $conn->prepare("INSERT INTO empresas (nombre, email, plan) VALUES (?, ?, 'basico')");
$stmt->execute([$nombre, $email]);
$empresa_id = $conn->lastInsertId();

// 2. Crear usuario vinculado como 'admin' y con contraseña encriptada
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol, empresa_id) VALUES (?, ?, ?, 'admin', ?)");
$stmt->execute([$nombre, $email, $hashed_password, $empresa_id]);

// 3. Login automático
$usuario_id = $conn->lastInsertId();
$_SESSION['usuario_id'] = $usuario_id;
$_SESSION['nombre'] = $nombre;
$_SESSION['rol'] = 'admin';
$_SESSION['empresa_id'] = $empresa_id;

// 4. Redirige al dashboard
header("Location: admin/dashboard.php");
exit;
