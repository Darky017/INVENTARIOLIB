<?php
require_once '../config.php';

if (!isset($_GET['id'])) {
    die("ID no especificado.");
}

$id = (int) $_GET['id'];

// Buscar info de la responsiva
$stmt = $pdo->prepare("SELECT nombre_archivo, ruta_archivo FROM responsivas WHERE id = ?");
$stmt->execute([$id]);
$responsiva = $stmt->fetch();

if (!$responsiva) {
    die("Archivo no encontrado.");
}

$ruta = '../uploads/responsivas/' . $responsiva['ruta_archivo'];

if (!file_exists($ruta)) {
    die("El archivo no existe en el servidor.");
}

// Forzar descarga con el nombre original
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($responsiva['nombre_archivo']) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($ruta));
readfile($ruta);
exit;
?>
