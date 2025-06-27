<?php
session_start();
require_once '../config.php';

// Verificar si se proporciona un ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de impresora no válido";
    header("Location: impresoras_list.php");
    exit;
}

$impresora_id = (int) $_GET['id'];

try {
    // Obtener información de la impresora antes de eliminar para la auditoría
    $stmt = $pdo->prepare("SELECT marca, modelo, numero_serie, usuario_id FROM impresoras WHERE id = ?");
    $stmt->execute([$impresora_id]);
    $impresora = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$impresora) {
        $_SESSION['error'] = "Impresora no encontrada";
        header("Location: impresoras_list.php");
        exit;
    }

    // Eliminar la impresora
    $stmt = $pdo->prepare("DELETE FROM impresoras WHERE id = ?");
    $stmt->execute([$impresora_id]);

    // Registrar auditoría
    if (isset($impresora['usuario_id'])) {
        registrar_auditoria($pdo, $impresora['usuario_id'], 'ELIMINAR', 
            "Se eliminó la impresora {$impresora['marca']} {$impresora['modelo']} con serie {$impresora['numero_serie']}", 
            'impresoras', $impresora_id);
    }

    $_SESSION['success'] = "Impresora eliminada exitosamente";

} catch (Exception $e) {
    $_SESSION['error'] = "Error al eliminar la impresora: " . $e->getMessage();
}

header("Location: impresoras_list.php");
exit;
?> 