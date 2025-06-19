<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO equipo_cel_sim (id_celular, numero, n_serie, version, compania, es_plan, estado, fecha_asignacion)
                           VALUES (NULL, :numero, :n_serie, :version, :compania, :es_plan, 'Disponible', :fecha_asignacion)");
    $stmt->execute([
        'numero' => $_POST['numero'],
        'n_serie' => $_POST['n_serie'],
        'version' => $_POST['version'],
        'compania' => $_POST['compania'],
        'es_plan' => $_POST['es_plan'],
        'fecha_asignacion' => $_POST['fecha_asignacion']
    ]);

    header("Location: sims_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar SIM</title>
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container { max-width: 450px; }
        .card { margin-top: 30px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-info text-white text-center">
            <h5>Registrar SIM (sin celular)</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label><strong>Número:</strong></label>
                    <input type="text" name="numero" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><strong>Número de Serie:</strong></label>
                    <input type="text" name="n_serie" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><strong>Versión:</strong></label>
                    <input type="text" name="version" class="form-control">
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
                        <option value="No">No</option>
                        <option value="Sí">Sí</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>Fecha de Asignación:</strong></label>
                    <input type="date" name="fecha_asignacion" class="form-control" optional>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="sims_list.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
