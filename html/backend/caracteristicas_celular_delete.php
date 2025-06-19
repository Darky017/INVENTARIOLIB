<?php
session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: celulares_list.php");
    exit();
}

// Eliminar directamente si existen características
$stmt = $pdo->prepare("DELETE FROM caracteristicas_celular WHERE id_celular = ?");
$stmt->execute([$id]);

// Redirigir de vuelta a la vista de detalles del celular
header("Location: celulares_detalles.php?id=$id");
exit();
