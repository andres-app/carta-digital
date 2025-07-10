<?php
require_once 'includes/db.php';
session_start();

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validar campos vacíos
if (empty($email) || empty($password)) {
    $error = "Por favor ingresa tu correo y contraseña.";
    include 'login.php';
    exit;
}

// Buscar usuario
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

// Comparar credenciales (modo simple)
if ($usuario && $password === $usuario['password']) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['rol'] = $usuario['rol'];
    $_SESSION['empresa_id'] = $usuario['empresa_id'];

    $redirect = $usuario['rol'] === 'superadmin' ? 'superadmin/index.php' : 'admin/dashboard.php';
    header("Location: $redirect");
    exit;
} else {
    $error = "Credenciales inválidas. Intenta nuevamente.";
    include 'login.php';
    exit;
}
