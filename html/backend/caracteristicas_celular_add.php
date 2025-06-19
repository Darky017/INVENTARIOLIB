<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: celulares_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO caracteristicas_celular (id_celular, ram, almacenamiento, procesador) VALUES (:id_celular, :ram, :almacenamiento, :procesador)");
    $stmt->execute([
        'id_celular' => $id,
        'ram' => $_POST['ram'],
        'almacenamiento' => $_POST['almacenamiento'],
        'procesador' => $_POST['procesador']
    ]);

    header("Location: celulares_detalles.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Características</title>
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5>Registrar Características</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                    <div class="form-group">
                    <label>RAM:</label>
                    <select name="ram" class="form-control" required>
                        <option value="4">4 GB</option>
                        <option value="6">6 GB</option>
                        <option value="8">8 GB</option>
                        <option value="12">12 GB</option>
                        <option value="16">16 GB</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Almacenamiento:</label>
                    <select name="almacenamiento" class="form-control" required>
                        <option value="32">32 GB</option>
                        <option value="64">64 GB</option>
                        <option value="128">128 GB</option>
                        <option value="256">256 GB</option>
                        <option value="512">512 GB</option>
                        <option value="1tb">1 TB</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Procesador:</label>
                    <input type="text" name="procesador" class="form-control" required>
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
