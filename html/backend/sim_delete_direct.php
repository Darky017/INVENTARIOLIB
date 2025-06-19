<?php
session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: sims_list.php");
    exit();
}

$sim_id = $_GET['id'];

// Verificamos si existe
$stmt = $pdo->prepare("SELECT id FROM equipo_cel_sim WHERE id = :id");
$stmt->execute(['id' => $sim_id]);
$sim = $stmt->fetch();

if ($sim) {
    // Eliminar
    $stmt = $pdo->prepare("DELETE FROM equipo_cel_sim WHERE id = :id");
    $stmt->execute(['id' => $sim_id]);
}

// Siempre redirige de vuelta
header("Location: sims_list.php");
exit();
?>
