<?php
ob_start(); // Iniciar buffer de salida
if (session_status() == PHP_SESSION_NONE) session_start();

require_once '../config.php';
require_once 'header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: equipos_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO software (equipo_id, rustdesk_id, rustdesk_password) VALUES (:equipo_id, :rustdesk_id, :rustdesk_password)");
    $stmt->execute([
        'equipo_id' => $id,
        'rustdesk_id' => $_POST['rustdesk_id'],
        'rustdesk_password' => $_POST['rustdesk_password']
    ]);

    header("Location: equipos_detalles.php?id=$id");
    exit();
}
ob_end_flush(); // Enviar buffer de salida
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Software</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container { max-width: 400px; }
        .card { margin-top: 30px; border-radius: 10px; }
        .form-group { margin-bottom: 10px; }
        .btn { padding: 6px 12px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5>Registrar Software</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="equipo_id" value="<?= $id ?>">
                <div class="form-group">
                    <label><strong>RustDesk ID:</strong></label>
                    <input type="text" name="rustdesk_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><strong>Contraseña:</strong></label>
                    <input type="text" name="rustdesk_password" class="form-control" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="equipos_detalles.php?id=<?= $id ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
