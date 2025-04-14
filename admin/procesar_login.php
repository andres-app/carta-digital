<?php
require_once '../includes/db.php';
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM empresas WHERE email = ?");
$stmt->execute([$email]);
$empresa = $stmt->fetch();

// 👇 Comparación directa SIN hash (solo para pruebas)
if ($empresa && $password === $empresa['password']) {
    $_SESSION['empresa_id'] = $empresa['id'];
    $_SESSION['empresa_nombre'] = $empresa['nombre'];
    header("Location: dashboard.php");
} else {
    echo "Credenciales inválidas. <a href='login.php'>Intentar de nuevo</a>";
}
