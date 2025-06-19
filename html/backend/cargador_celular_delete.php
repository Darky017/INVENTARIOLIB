<?php
session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null; // ID del celular

if (!$id) {
    header("Location: celulares_list.php");
    exit();
}

// Eliminar cargador asociado al celular
$stmt = $pdo->prepare("DELETE FROM cargador_celular WHERE id_celular = ?");
$stmt->execute([$id]);

// Redirigir a la vista de detalles del celular
header("Location: celulares_detalles.php?id=$id");
exit();
