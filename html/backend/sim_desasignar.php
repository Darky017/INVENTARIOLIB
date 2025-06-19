<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;   // ID del celular
$sim = $_GET['sim'] ?? null; // ID de la relación equipo_cel_sim

if (!$id || !$sim) {
    header("Location: celulares_list.php");
    exit();
}

// Verificamos que la SIM exista y esté asociada al celular
$stmt = $pdo->prepare("SELECT id FROM equipo_cel_sim WHERE id = :sim AND id_celular = :id");
$stmt->execute([
    'sim' => $sim,
    'id' => $id
]);
$simExists = $stmt->fetch();

if ($simExists) {
    // Desasociar: poner id_celular en NULL
    $stmt = $pdo->prepare("UPDATE equipo_cel_sim SET id_celular = NULL, fecha_asignacion = NULL WHERE id = :sim");
    $stmt->execute(['sim' => $sim]);
}

// Redirigir al detalle del celular
header("Location: celulares_detalles.php?id=$id");
exit();
?>
