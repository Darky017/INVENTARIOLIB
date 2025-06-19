<?php
session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = $_GET['id'];
$errores = [];

// Obtener datos actuales
$stmt = $pdo->prepare("SELECT * FROM caracteristicas_tecnicas WHERE equipo_id = ?");
$stmt->execute([$id]);
$caracteristicas = $stmt->fetch();

if (!$caracteristicas) {
    die("No se encontraron características técnicas para este equipo.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $procesador = $_POST['procesador'];
    $ram = $_POST['ram'];
    $almacenamiento_tipo = $_POST['almacenamiento_tipo'];
    $almacenamiento_capacidad = $_POST['almacenamiento_capacidad'];
    $almacenamiento_tipo2 = $_POST['almacenamiento_tipo2'] ?? '';
    $almacenamiento_capacidad2 = $_POST['almacenamiento_capacidad2'] ?? '';
    $tarjeta_video = $_POST['tarjeta_video'];
    $estado_bateria = $_POST['estado_bateria'];

    if (empty($procesador) || empty($ram) || empty($almacenamiento_tipo) || empty($almacenamiento_capacidad) || empty($estado_bateria)) {
        $errores[] = "Todos los campos obligatorios deben estar completos.";
    }

    // Si almacenamiento secundario está vacío, se guarda como "NO aplica"
    if (empty($almacenamiento_tipo2)) {
        $almacenamiento_tipo2 = "NO aplica";
    }

    if (empty($almacenamiento_capacidad2)) {
        $almacenamiento_capacidad2 = "NO aplica";
    }

    if (empty($errores)) {
        $stmt = $pdo->prepare("UPDATE caracteristicas_tecnicas 
            SET procesador = :procesador,
                ram = :ram,
                almacenamiento_tipo = :almacenamiento_tipo,
                almacenamiento_capacidad = :almacenamiento_capacidad,
                almacenamiento_tipo2 = :almacenamiento_tipo2,
                almacenamiento_capacidad2 = :almacenamiento_capacidad2,
                tarjeta_video = :tarjeta_video,
                estado_bateria = :estado_bateria
            WHERE equipo_id = :equipo_id");

        $stmt->execute([
            'procesador' => $procesador,
            'ram' => $ram,
            'almacenamiento_tipo' => $almacenamiento_tipo,
            'almacenamiento_capacidad' => $almacenamiento_capacidad,
            'almacenamiento_tipo2' => $almacenamiento_tipo2,
            'almacenamiento_capacidad2' => $almacenamiento_capacidad2,
            'tarjeta_video' => $tarjeta_video,
            'estado_bateria' => $estado_bateria,
            'equipo_id' => $id
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
    <title>Editar Características Técnicas</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container { max-width: 500px; }
        .card { margin-top: 30px; border-radius: 10px; }
        .card-header { padding: 10px; }
        .form-group { margin-bottom: 8px; }
        .btn { padding: 6px 12px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-warning text-white text-center">
            <h5>Editar Características Técnicas</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label><strong>Procesador:</strong></label>
                    <input type="text" name="procesador" class="form-control" value="<?= htmlspecialchars($caracteristicas['procesador']) ?>" required>
                </div>
                <div class="form-group">
                    <label><strong>RAM:</strong></label>
                    <select name="ram" class="form-control" required>
                        <?php
                        $rams = ["4GB", "8GB", "12GB", "16GB", "32GB", "64GB"];
                        foreach ($rams as $ram) {
                            $selected = $caracteristicas['ram'] === $ram ? 'selected' : '';
                            echo "<option value='$ram' $selected>$ram</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>Almacenamiento Principal:</strong></label>
                    <div class="d-flex">
                        <select name="almacenamiento_tipo" class="form-control mr-2" required>
                            <?php
                            $tipos = ["HDD", "SSD", "NVMe", "Otro"];
                            foreach ($tipos as $tipo) {
                                $selected = $caracteristicas['almacenamiento_tipo'] === $tipo ? 'selected' : '';
                                echo "<option value='$tipo' $selected>$tipo</option>";
                            }
                            ?>
                        </select>
                        <input type="text" name="almacenamiento_capacidad" class="form-control" value="<?= htmlspecialchars($caracteristicas['almacenamiento_capacidad']) ?>" placeholder="Capacidad (GB)" required>
                    </div>
                </div>
                <div class="form-group">
                    <label><strong>Almacenamiento Secundario:</strong></label>
                    <div class="d-flex">
                        <select name="almacenamiento_tipo2" class="form-control mr-2">
                            <option value="">-- Seleccionar --</option>
                            <?php
                            foreach ($tipos as $tipo) {
                                $selected = $caracteristicas['almacenamiento_tipo2'] === $tipo ? 'selected' : '';
                                echo "<option value='$tipo' $selected>$tipo</option>";
                            }
                            ?>
                        </select>
                        <input type="text" name="almacenamiento_capacidad2" class="form-control" value="<?= htmlspecialchars($caracteristicas['almacenamiento_capacidad2']) ?>" placeholder="Capacidad (GB)">
                    </div>
                </div>
                <div class="form-group">
                    <label><strong>Tarjeta de Video:</strong></label>
                    <input type="text" name="tarjeta_video" class="form-control" value="<?= htmlspecialchars($caracteristicas['tarjeta_video']) ?>">
                </div>
                <div class="form-group">
                    <label><strong>Estado de la Batería:</strong></label>
                    <select name="estado_bateria" class="form-control" required>
                        <?php
                        $estados = ["Nuevo", "Medio Uso", "Buena", "Funcional", "Mala"];
                        foreach ($estados as $estado) {
                            $selected = $caracteristicas['estado_bateria'] === $estado ? 'selected' : '';
                            echo "<option value='$estado' $selected>$estado</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-warning text-white">Actualizar</button>
                    <a href="equipos_detalles.php?id=<?= $id ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
