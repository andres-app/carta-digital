<?php
require_once 'includes/db.php';
session_start();

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    $error = "Por favor ingresa tu correo y contraseña.";
    include 'login.php';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario) {
    // INTENTA LOGIN CON HASH
    if (password_verify($password, $usuario['password'])) {
        // Login OK (hash)
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['empresa_id'] = $usuario['empresa_id'];
        $redirect = $usuario['rol'] === 'superadmin' ? 'superadmin/index.php' : 'admin/dashboard.php';
        header("Location: $redirect");
        exit;
    }
    // INTENTA LOGIN CON PLANO (antiguo)
    elseif ($password === $usuario['password']) {
        // 1. ACTUALIZA a HASH la contraseña en la BD
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt2 = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt2->execute([$hashed_password, $usuario['id']]);

        // 2. Login OK, igual que antes
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['empresa_id'] = $usuario['empresa_id'];
        $redirect = $usuario['rol'] === 'superadmin' ? 'superadmin/index.php' : 'admin/dashboard.php';
        header("Location: $redirect");
        exit;
    }
}

// SI FALLA TODO:
$error = "Credenciales inválidas. Intenta nuevamente.";
include 'login.php';
exit;

