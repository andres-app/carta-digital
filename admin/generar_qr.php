<?php
require_once '../librerias/phpqrcode/qrlib.php';
require_once '../librerias/fpdf/fpdf.php';
require_once '../includes/db.php';
session_start();

// Verificar si es admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    die('Acceso denegado');
}

// Obtener local
$local_id = $_GET['local_id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM locales WHERE id = ?");
$stmt->execute([$local_id]);
$local = $stmt->fetch();

if (!$local) {
    die('Local no encontrado');
}

// URL de la carta
$url = 'http://localhost/carta-digital/public/carta.php?slug=' . urlencode($local['slug']);

// Directorio temporal
$dir = '../assets/qr/';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

// Archivo QR
$qr_file = $dir . 'qr_local_' . $local['id'] . '.png';
QRcode::png($url, $qr_file, 'H', 10, 2);

// Crear PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Logo opcional arriba
//$pdf->Image('../assets/img/logo-tucarta.png', 10, 10, 30);

// Título
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 20, utf8_decode($local['nombre']), 0, 1, 'C');

// Espaciado
$pdf->Ln(10);

// QR centrado
$pdf->Image($qr_file, 60, 50, 90, 90);

// Espaciado después del QR
$pdf->Ln(100);

// Texto debajo
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(0, 10, utf8_decode('Escanea el código para ver nuestra carta digital'), 0, 1, 'C');

// Output del PDF
$pdf->Output('I', 'qr_carta_' . $local['slug'] . '.pdf');
exit;
?>
