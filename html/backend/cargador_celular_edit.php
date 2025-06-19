<?php
session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: celulares_list.php");
    exit();
}

$celular_id = $_GET['id'];
$errors = [];

// Obtener datos del cargador
$stmt = $pdo->prepare("SELECT * FROM cargador_celular WHERE id_celular = :id");
$stmt->execute(['id' => $celular_id]);
$cargador = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marca = $_POST['marca'];
    $n_serie = $_POST['n_serie'];
    $modelo = $_POST['modelo'];
    $watts = $_POST['watts'];

    if (empty($marca) || empty($n_serie) || empty($modelo) || empty($watts)) {
        $errors[] = "Todos los campos son obligatorios.";
    }

    if (empty($errors)) {
        if ($cargador) {
            $stmt = $pdo->prepare("UPDATE cargador_celular SET marca = :marca, n_serie = :n_serie, modelo = :modelo, watts = :watts WHERE id_celular = :id");
        } else {
            $stmt = $pdo->prepare("INSERT INTO cargador_celular (id_celular, marca, n_serie, modelo, watts) VALUES (:id, :marca, :n_serie, :modelo, :watts)");
        }

        $stmt->execute([
            'id' => $celular_id,
            'marca' => $marca,
            'n_serie' => $n_serie,
            'modelo' => $modelo,
            'watts' => $watts
        ]);

        header("Location: celulares_detalles.php?id=$celular_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cargador Celular</title>
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
            <h5><?= $cargador ? 'Editar Cargador' : 'Registrar Cargador' ?></h5>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="marca"><strong>Marca:</strong></label>
                    <input type="text" name="marca" id="marca" class="form-control" value="<?= htmlspecialchars($cargador['marca'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="n_serie"><strong>Número de Serie:</strong></label>
                    <input type="text" name="n_serie" id="n_serie" class="form-control" value="<?= htmlspecialchars($cargador['n_serie'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="modelo"><strong>Modelo:</strong></label>
                    <input type="text" name="modelo" id="modelo" class="form-control" value="<?= htmlspecialchars($cargador['modelo'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="watts"><strong>Watts:</strong></label>
                    <input type="text" name="watts" id="watts" class="form-control" value="<?= htmlspecialchars($cargador['watts'] ?? '') ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="celulares_detalles.php?id=<?= $celular_id ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
