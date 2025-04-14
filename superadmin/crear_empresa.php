<?php
require_once '../includes/db.php';

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO empresas (nombre, email, password) VALUES (?, ?, ?)");
$stmt->execute([$nombre, $email, $password]);

header("Location: index.php");
exit;
