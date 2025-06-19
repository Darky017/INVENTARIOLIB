<?php
session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = $_GET['id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estado = $_POST['estado'] ?? 'Desactivado';
    $notas = $_POST['notas'];

    if ($estado === 'Activado') {
        $fecha_registro = $_POST['fecha_registro'] ?? '';
        $fecha_garantia = $_POST['fecha_garantia'] ?? '';

        if (empty($fecha_registro) || empty($fecha_garantia)) {
            $errors[] = "Debes ingresar ambas fechas si la garantía está Activada.";
        } else {
            if (strtotime($fecha_garantia) < strtotime(date('Y-m-d'))) {
                $estado = 'Desactivado';
            }
        }
    } else {
        $fecha_registro = null;
        $fecha_garantia = null;
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO garantia (equipo_id, fecha_registro, fecha_garantia, notas, estado) 
                               VALUES (:id, :fecha_registro, :fecha_garantia, :notas, :estado)");
        $stmt->execute([
            'id' => $id,
            'fecha_registro' => $fecha_registro,
            'fecha_garantia' => $fecha_garantia,
            'notas' => $notas,
            'estado' => $estado
        ]);

        header("Location: equipos_detalles.php?id=$id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Garantía</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container { max-width: 500px; }
        .card { margin-top: 30px; border-radius: 10px; }
        .card-header { padding: 10px; }
        .form-group { margin-bottom: 10px; }
        .btn { padding: 8px 15px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5>Registrar Garantía</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label><strong>Estado de la Garantía:</strong></label>
                    <select name="estado" class="form-control" id="estadoSelect">
                        <option value="Activado">Activado</option>
                        <option value="Desactivado">Desactivado</option>
                    </select>
                </div>

                <div class="form-group" id="fechaInicioGroup">
                    <label><strong>Fecha de Inicio:</strong></label>
                    <input type="date" name="fecha_registro" class="form-control">
                </div>

                <div class="form-group" id="fechaFinGroup">
                    <label><strong>Fecha Finalización:</strong></label>
                    <input type="date" name="fecha_garantia" class="form-control">
                </div>

                <div class="form-group">
                    <label><strong>Comentarios:</strong></label>
                    <textarea name="notas" class="form-control" rows="2"></textarea>
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    const estadoSelect = document.getElementById("estadoSelect");
    const fechaInicioGroup = document.getElementById("fechaInicioGroup");
    const fechaFinGroup = document.getElementById("fechaFinGroup");

    function toggleFechas() {
        const activado = estadoSelect.value === "Activado";
        fechaInicioGroup.style.display = activado ? "block" : "none";
        fechaFinGroup.style.display = activado ? "block" : "none";
    }

    estadoSelect.addEventListener("change", toggleFechas);
    toggleFechas(); // ejecución inicial
});
</script>

</body>
</html>
