<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: equipos_list.php");
    exit();
}

// Verificar si ya existe un registro de BitLocker para este equipo
$stmt = $pdo->prepare("SELECT * FROM bitlocker WHERE equipo_id = :id");
$stmt->execute(['id' => $id]);
$bitlocker = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estado = $_POST['estado'];
    $clave_recuperacion = ($estado === "Activado" && !empty($_POST['clave_recuperacion'])) ? $_POST['clave_recuperacion'] : "No aplica";
    $ID_Objeto_Dispositivo = ($estado === "Activado" && !empty($_POST['ID_Objeto_Dispositivo'])) ? $_POST['ID_Objeto_Dispositivo'] : "No aplica";

    if ($bitlocker) {
        $stmt = $pdo->prepare("UPDATE bitlocker SET estado = :estado, clave_recuperacion = :clave_recuperacion, ID_Objeto_Dispositivo = :ID_Objeto_Dispositivo WHERE equipo_id = :id");
    } else {
        $stmt = $pdo->prepare("INSERT INTO bitlocker (equipo_id, estado, clave_recuperacion, ID_Objeto_Dispositivo) VALUES (:id, :estado, :clave_recuperacion, :ID_Objeto_Dispositivo)");
    }

    $stmt->execute([
        'id' => $id,
        'estado' => $estado,
        'clave_recuperacion' => $clave_recuperacion,
        'ID_Objeto_Dispositivo' => $ID_Objeto_Dispositivo
    ]);

    exit(header("Location: equipos_detalles.php?id=$id"));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar/Editar BitLocker</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container { max-width: 450px; }
        .card { margin-top: 30px; border-radius: 10px; }
        .form-group { margin-bottom: 10px; }
        .btn { padding: 6px 12px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5><?= $bitlocker ? 'Editar' : 'Registrar' ?> BitLocker</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label><strong>Estado de BitLocker:</strong></label>
                    <select name="estado" id="estado_bitlocker" class="form-control" required onchange="toggleCampos()">
                        <option value="Activado" <?= ($bitlocker && $bitlocker['estado'] === "Activado") ? 'selected' : '' ?>>Activado</option>
                        <option value="Desactivado" <?= ($bitlocker && $bitlocker['estado'] === "Desactivado") ? 'selected' : '' ?>>Desactivado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>ID de Objeto de Dispositivo:</strong></label>
                    <input type="text" name="ID_Objeto_Dispositivo" id="ID_Objeto_Dispositivo" class="form-control" required
                           value="<?= htmlspecialchars($bitlocker['ID_Objeto_Dispositivo'] ?? '') ?>" <?= (!$bitlocker || $bitlocker['estado'] === "Desactivado") ? 'disabled' : '' ?>>
                </div>
                <div class="form-group" id="clave_group">
                    <label><strong>Clave de Recuperación:</strong></label>
                    <input type="text" name="clave_recuperacion" id="clave_recuperacion" class="form-control"
                           value="<?= htmlspecialchars($bitlocker['clave_recuperacion'] ?? '') ?>" <?= (!$bitlocker || $bitlocker['estado'] === "Desactivado") ? 'disabled' : '' ?>>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="equipos_detalles.php?id=<?= $id ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleCampos() {
        const estado = document.getElementById("estado_bitlocker").value;
        const claveInput = document.getElementById("clave_recuperacion");
        const idObjetoInput = document.getElementById("ID_Objeto_Dispositivo");

        if (estado === "Desactivado") {
            claveInput.value = "No aplica";
            claveInput.setAttribute("disabled", "true");

            idObjetoInput.value = "No aplica";
            idObjetoInput.setAttribute("disabled", "true");
        } else {
            claveInput.removeAttribute("disabled");
            idObjetoInput.removeAttribute("disabled");
        }
    }

    document.addEventListener("DOMContentLoaded", toggleCampos);
</script>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
