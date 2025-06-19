<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;   // ID del celular
$sim = $_GET['sim'] ?? null; // ID de la SIM (equipo_cel_sim)

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
    // Eliminar directamente
    $stmt = $pdo->prepare("DELETE FROM equipo_cel_sim WHERE id = :sim AND id_celular = :id");
    $stmt->execute([
        'sim' => $sim,
        'id' => $id
    ]);
}

// Redirigir de regreso al detalle del celular
header("Location: celulares_detalles.php?id=$id");
exit();
?>
