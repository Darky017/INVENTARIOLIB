<?php
session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$garantia_id = $_GET['id'];
$errors = [];

// Obtener datos actuales de la garantía
$stmt = $pdo->prepare("SELECT * FROM garantia WHERE id = ?");
$stmt->execute([$garantia_id]);
$garantia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$garantia) {
    echo "Garantía no encontrada.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estado = $_POST['estado'] ?? 'Inactivo';
    $notas = $_POST['notas'] ?? '';
    $fecha_registro = $_POST['fecha_registro'] ?? null;
    $fecha_garantia = $_POST['fecha_garantia'] ?? null;

    if ($estado === 'Activo') {
        if (empty($fecha_registro) || empty($fecha_garantia)) {
            $errors[] = "Debes ingresar ambas fechas si la garantía está Activa.";
        } elseif (strtotime($fecha_garantia) < strtotime(date('Y-m-d'))) {
            $estado = 'Inactivo'; // Desactivar automáticamente si la fecha ya pasó
        }
    } else {
        $fecha_registro = null;
        $fecha_garantia = null;
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE garantia SET fecha_registro = :fecha_registro, fecha_garantia = :fecha_garantia, notas = :notas, estado = :estado WHERE id = :id");
        $stmt->execute([
            'fecha_registro' => $fecha_registro,
            'fecha_garantia' => $fecha_garantia,
            'notas' => $notas,
            'estado' => $estado,
            'id' => $garantia_id
        ]);

        header("Location: equipos_detalles.php?id=" . $garantia['equipo_id']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Garantía</title>
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
        <div class="card-header bg-warning text-white text-center">
            <h5>Editar Garantía</h5>
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
                        <option value="Activo" <?= $garantia['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="Inactivo" <?= $garantia['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <div class="form-group" id="fechaInicioGroup">
                    <label><strong>Fecha de Inicio:</strong></label>
                    <input type="date" name="fecha_registro" class="form-control" value="<?= htmlspecialchars($garantia['fecha_registro']) ?>">
                </div>

                <div class="form-group" id="fechaFinGroup">
                    <label><strong>Fecha Finalización:</strong></label>
                    <input type="date" name="fecha_garantia" class="form-control" value="<?= htmlspecialchars($garantia['fecha_garantia']) ?>">
                </div>

                <div class="form-group">
                    <label><strong>Comentarios:</strong></label>
                    <textarea name="notas" class="form-control" rows="2"><?= htmlspecialchars($garantia['notas']) ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                    <a href="equipos_detalles.php?id=<?= $garantia['equipo_id'] ?>" class="btn btn-secondary">Cancelar</a>
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
        const activo = estadoSelect.value === "Activo";
        fechaInicioGroup.style.display = activo ? "block" : "none";
        fechaFinGroup.style.display = activo ? "block" : "none";
    }

    estadoSelect.addEventListener("change", toggleFechas);
    toggleFechas(); // ejecución inicial
});
</script>

</body>
</html>
