<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: celulares_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO esim (id_celular, numero, n_serie, compania, es_plan, fecha_asignacion) VALUES (:id_celular, :numero, :n_serie, :compania, :es_plan, :fecha_asignacion)");
    $stmt->execute([
        'id_celular' => $id,
        'numero' => $_POST['numero'],
        'n_serie' => $_POST['n_serie'],
        'compania' => $_POST['compania'],
        'es_plan' => $_POST['es_plan'],
        'fecha_asignacion' => $_POST['fecha_asignacion']
    ]);

    header("Location: celulares_detalles.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar eSIM</title>
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5>Registrar eSIM</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Número:</label>
                    <input type="text" name="numero" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Número de Serie:</label>
                    <input type="text" name="n_serie" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><strong>Compañía:</strong></label>
                    <select name="compania" class="form-control" required>
                        <option value="">Selecciona</option>
                        <option>AT&T</option>
                        <option>Movistar</option>
                        <option>Bait</option>
                        <option>Telcel</option>
                        <option>Virgin</option>
                        <option>Nextel</option>
                        <option>Unefon</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>¿Es Plan?</strong></label>
                    <select name="es_plan" class="form-control" required>
                        <option value="">Selecciona</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>Fecha de Asignación:</strong></label>
                    <input type="date" name="fecha_asignacion" class="form-control" required>
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
