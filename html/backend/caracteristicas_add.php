<?php
session_start();
require_once '../config.php';

// Mostrar errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validar si hay ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = (int) $_GET['id'];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar y sanitizar entradas
    $procesador = trim($_POST['procesador'] ?? '');
    $ram = $_POST['ram'] ?? '';
    $almacenamiento_tipo = $_POST['almacenamiento_tipo'] ?? '';
    $almacenamiento_capacidad = trim($_POST['almacenamiento_capacidad'] ?? '');
    $almacenamiento_tipo2 = trim($_POST['almacenamiento_tipo2'] ?? '');
    $almacenamiento_capacidad2 = trim($_POST['almacenamiento_capacidad2'] ?? '');
    $tarjeta_video = trim($_POST['tarjeta_video'] ?? '');
    $estado_bateria = $_POST['estado_bateria'] ?? '';

    // Validación básica
    if (empty($procesador) || empty($ram) || empty($almacenamiento_tipo) || empty($almacenamiento_capacidad) || empty($estado_bateria)) {
        $errores[] = "Todos los campos obligatorios deben completarse.";
    }

    // Si campos secundarios están vacíos, usar "NO aplica"
   // Si campos secundarios están vacíos, usar valores válidos
if (empty($almacenamiento_tipo2)) {
    $almacenamiento_tipo2 = "No aplica"; // evitar valor no permitido
}
if (empty($almacenamiento_capacidad2)) {
    $almacenamiento_capacidad2 = "No aplica"; // este sí puede ser texto libre
}


    // Intentar insertar si no hay errores
    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO caracteristicas_tecnicas (
                equipo_id, procesador, ram, 
                almacenamiento_tipo, almacenamiento_capacidad, 
                almacenamiento_tipo2, almacenamiento_capacidad2, 
                tarjeta_video, estado_bateria
            ) VALUES (
                :equipo_id, :procesador, :ram, 
                :almacenamiento_tipo, :almacenamiento_capacidad, 
                :almacenamiento_tipo2, :almacenamiento_capacidad2, 
                :tarjeta_video, :estado_bateria)");

            $stmt->execute([
                ':equipo_id' => $id,
                ':procesador' => $procesador,
                ':ram' => $ram,
                ':almacenamiento_tipo' => $almacenamiento_tipo,
                ':almacenamiento_capacidad' => $almacenamiento_capacidad,
                ':almacenamiento_tipo2' => $almacenamiento_tipo2,
                ':almacenamiento_capacidad2' => $almacenamiento_capacidad2,
                ':tarjeta_video' => $tarjeta_video,
                ':estado_bateria' => $estado_bateria
            ]);

            header("Location: equipos_detalles.php?id=$id");
            exit();
        } catch (PDOException $e) {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Características Técnicas</title>
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
        <div class="card-header bg-primary text-white text-center">
            <h5>Características Técnicas</h5>
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
                    <input type="text" name="procesador" class="form-control" required>
                </div>

                <div class="form-group">
                    <label><strong>RAM:</strong></label>
                    <select name="ram" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="4GB">4GB</option>
                        <option value="8GB">8GB</option>
                        <option value="12GB">12GB</option>
                        <option value="16GB">16GB</option>
                        <option value="32GB">32GB</option>
                        <option value="64GB">64GB</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><strong>Almacenamiento Principal:</strong></label>
                    <div class="d-flex">
                        <select name="almacenamiento_tipo" class="form-control mr-2" required>
                            <option value="">Tipo</option>
                            <option value="HDD">HDD</option>
                            <option value="SSD">SSD</option>
                            <option value="NVMe">NVMe</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <input type="text" name="almacenamiento_capacidad" class="form-control" placeholder="Capacidad (GB)" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Almacenamiento Secundario:</strong></label>
                    <div class="d-flex">
                        <select name="almacenamiento_tipo2" class="form-control mr-2">
                            <option value="">No Aplica</option>
                            <option value="HDD">HDD</option>
                            <option value="SSD">SSD</option>
                            <option value="NVMe">NVMe</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <input type="text" name="almacenamiento_capacidad2" class="form-control" placeholder="Capacidad (GB)">
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Tarjeta de Video:</strong></label>
                    <input type="text" name="tarjeta_video" class="form-control">
                </div>

                <div class="form-group">
                    <label><strong>Estado de la Batería:</strong></label>
                    <select name="estado_bateria" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="Nuevo">Nuevo</option>
                        <option value="Medio Uso">Medio Uso</option>
                        <option value="Buena">Buena</option>
                        <option value="Funcional">Funcional</option>
                        <option value="Mala">Mala</option>
                    </select>
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
