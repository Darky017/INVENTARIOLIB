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

$usuario_id = $_SESSION['superusuario']['id'] ?? null;
registrar_auditoria($pdo, $usuario_id, 'reposicion', 'Eliminación de equipo por reposición', 'equipo_computo', $id);

// Redirigir a los detalles del celular
header("Location: celulares_detalles.php?id=$id");
exit();
