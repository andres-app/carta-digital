<?php
require_once 'includes/db.php';
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

// Buscar al usuario por email
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario && $password === $usuario['password']) { // Comparación simple (sin hash aún)
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['rol'] = $usuario['rol'];
    $_SESSION['empresa_id'] = $usuario['empresa_id'];

    if ($usuario['rol'] === 'superadmin') {
        header("Location: superadmin/index.php");
    } elseif ($usuario['rol'] === 'admin') {
        header("Location: admin/dashboard.php");
    }
    exit;
} else {
    echo "Credenciales inválidas. <a href='login.php'>Intentar de nuevo</a>";
}
