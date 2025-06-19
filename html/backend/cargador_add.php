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
    $marca = $_POST['marca'];
    $num_serie = $_POST['numero_serie'];
    $modelo = $_POST['modelo'];
    $watts = $_POST['watts'];

    // Validación
    if (empty($marca) || empty($num_serie) || empty($modelo) || empty($watts)) {
        $errors[] = "Todos los campos son obligatorios.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO cargador (equipo_id, marca, numero_serie, modelo, watts) VALUES (:id, :marca, :numero_serie, :modelo, :watts)");
        $stmt->execute(['id' => $id, 'marca' => $marca, 'numero_serie' => $num_serie, 'modelo' => $modelo, 'watts' => $watts]);

        header("Location: equipos_detalles.php?id=$id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Cargador</title>
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
            <h5>Agregar Cargador</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="marca"><strong>Marca:</strong></label>
                    <input type="text" name="marca" id="marca" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="numero_serie"><strong>Número de Serie:</strong></label>
                    <input type="text" name="numero_serie" id="numero_serie" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="modelo"><strong>Modelo:</strong></label>
                    <input type="text" name="modelo" id="modelo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="watts"><strong>Watts:</strong></label>
                    <input type="text" name="watts" id="watts" class="form-control" required>
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
