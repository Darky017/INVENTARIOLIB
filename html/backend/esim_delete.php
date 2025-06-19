<?php
session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null; // id del celular

if (!$id) {
    header("Location: celulares_list.php");
    exit();
}

// Eliminar eSIM directamente
$stmt = $pdo->prepare("DELETE FROM esim WHERE id_celular = ?");
$stmt->execute([$id]);

// Redirigir a los detalles del celular
header("Location: celulares_detalles.php?id=$id");
exit();
