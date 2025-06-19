<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;  // ID del celular
$sim_id = $_GET['sim_id'] ?? null;  // ID de la SIM para editar

if (!$id || !$sim_id) {
    header("Location: celulares_list.php");
    exit();
}


// Obtener la SIM existente
$stmt = $pdo->prepare("SELECT * FROM equipo_cel_sim WHERE id = :sim_id AND id_celular = :id_celular");
$stmt->execute([
    'sim_id' => $sim_id,
    'id_celular' => $id
]);
$sim = $stmt->fetch();

if (!$sim) {
    header("Location: celulares_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE equipo_cel_sim 
                           SET numero = :numero, n_serie = :n_serie, version = :version, 
                               compania = :compania, es_plan = :es_plan, fecha_asignacion = :fecha_asignacion 
                           WHERE id = :sim_id AND id_celular = :id_celular");
    $stmt->execute([
        'sim_id' => $sim_id,
        'id_celular' => $id,
        'numero' => $_POST['numero'],
        'n_serie' => $_POST['n_serie'],
        'version' => $_POST['version'],
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
    <title>Editar SIM</title>
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
        <div class="card-header bg-primary text-white text-center">
            <h5>Editar SIM Física</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id_celular" value="<?= $id ?>">
                <input type="hidden" name="sim_id" value="<?= $sim['id'] ?>">
                <div class="form-group">
                    <label><strong>Número:</strong></label>
                    <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($sim['numero']) ?>" required>
                </div>
                <div class="form-group">
                    <label><strong>Número de Serie:</strong></label>
                    <input type="text" name="n_serie" class="form-control" value="<?= htmlspecialchars($sim['n_serie']) ?>" required>
                </div>
                <div class="form-group">
                    <label><strong>Versión:</strong></label>
                    <input type="text" name="version" class="form-control" value="<?= htmlspecialchars($sim['version']) ?>">
                </div>
                <div class="form-group">
                    <label><strong>Compañía:</strong></label>
                    <select name="compania" class="form-control" required>
                        <option value="">Selecciona</option>
                        <option value="AT&T" <?= $sim['compania'] == 'AT&T' ? 'selected' : '' ?>>AT&T</option>
                        <option value="Movistar" <?= $sim['compania'] == 'Movistar' ? 'selected' : '' ?>>Movistar</option>
                        <option value="Bait" <?= $sim['compania'] == 'Bait' ? 'selected' : '' ?>>Bait</option>
                        <option value="Telcel" <?= $sim['compania'] == 'Telcel' ? 'selected' : '' ?>>Telcel</option>
                        <option value="Virgin" <?= $sim['compania'] == 'Virgin' ? 'selected' : '' ?>>Virgin</option>
                        <option value="Nextel" <?= $sim['compania'] == 'Nextel' ? 'selected' : '' ?>>Nextel</option>
                        <option value="Unefon" <?= $sim['compania'] == 'Unefon' ? 'selected' : '' ?>>Unefon</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>¿Es Plan?</strong></label>
                    <select name="es_plan" class="form-control" required>
                        <option value="No" <?= $sim['es_plan'] == 'No' ? 'selected' : '' ?>>No</option>
                        <option value="Sí" <?= $sim['es_plan'] == 'Sí' ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>

                <!-- Campo de Fecha de Asignación -->
                <div class="form-group">
                    <label><strong>Fecha de Asignación:</strong></label>
                    <input type="date" name="fecha_asignacion" class="form-control" value="<?= $sim['fecha_asignacion'] ?>" required>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                    <a href="celulares_detalles.php?id=<?= $id ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
