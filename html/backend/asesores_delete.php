<?php
session_start();
require_once '../config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID no especificado.");
}

$id = $_GET['id'];

// Primero, si tienes tablas relacionadas, elimina esos registros (si aplica)
// Por ejemplo:
// $pdo->prepare("DELETE FROM otra_tabla WHERE id_asesor = ?")->execute([$id]);

try {
    // Eliminar el asesor principal
    $stmt = $pdo->prepare("DELETE FROM asesores WHERE id = ?");
    $stmt->execute([$id]);

    // Redirigir al listado
    header("Location: asesores_list.php?msg=asesor_eliminado");
    exit();

} catch (Exception $e) {
    echo "Error al eliminar el asesor: " . $e->getMessage();
}
?>
