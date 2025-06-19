<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $equipo_id = $_POST['equipo_id'];
    $fecha_registro = $_POST['fecha_registro'];
    $fecha_garantia = $_POST['fecha_garantia'];
    $notas = $_POST['notas'];

    $stmt = $pdo->prepare("INSERT INTO garantia (equipo_id, fecha_registro, fecha_garantia, notas) 
        VALUES (:equipo_id, :fecha_registro, :fecha_garantia, :notas) 
        ON DUPLICATE KEY UPDATE fecha_registro = VALUES(fecha_registro), fecha_garantia = VALUES(fecha_garantia), notas = VALUES(notas)");

    $stmt->execute([
        'equipo_id' => $equipo_id,
        'fecha_registro' => $fecha_registro,
        'fecha_garantia' => $fecha_garantia,
        'notas' => $notas
    ]);

    header("Location: equipos_detalles.php?id=" . $equipo_id);
    exit();
}
?>
