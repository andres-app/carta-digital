<?php
$db_host = 'localhost';
$db_name = 'carta';
$db_user = 'root';
$db_pass = 'Dev2804751$$$'; // Cambia esto por tu contraseña si aplica

// ----------------------
// Configuración de rutas
// ----------------------
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
    $base_url = '/carta-digital';
} else {
    $base_url = '';
}
?>
