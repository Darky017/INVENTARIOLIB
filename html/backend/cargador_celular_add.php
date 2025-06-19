<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: celulares_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO cargador_celular (id_celular, n_serie, marca, modelo, watts) VALUES (:id_celular, :n_serie, :marca, :modelo, :watts)");
    $stmt->execute([
        'id_celular' => $id,
        'n_serie' => $_POST['n_serie'],
        'marca' => $_POST['marca'],
        'modelo' => $_POST['modelo'],
        'watts' => $_POST['watts']
    ]);

    header("Location: celulares_detalles.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Cargador</title>
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5>Registrar Cargador</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Número de Serie:</label>
                    <input type="text" name="n_serie" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Marca:</label>
                    <input type="text" name="marca" class="form-control">
                </div>
                <div class="form-group">
                    <label>Modelo:</label>
                    <input type="text" name="modelo" class="form-control">
                </div>
                <div class="form-group">
                    <label>Watts:</label>
                    <input type="text" name="watts" class="form-control">
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="celulares_detalles.php?id=<?= $id ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
