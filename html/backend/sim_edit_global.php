<?php
session_start();
require_once '../config.php';

$sim_id = $_GET['id'] ?? null;

if (!$sim_id) {
    header("Location: sims_list.php");
    exit();
}

// Obtener la SIM
$stmt = $pdo->prepare("SELECT * FROM equipo_cel_sim WHERE id = :id");
$stmt->execute(['id' => $sim_id]);
$sim = $stmt->fetch();

if (!$sim) {
    header("Location: sims_list.php");
    exit();
}

// Obtener celulares para el dropdown
$celulares = $pdo->query("SELECT id, marca, modelo FROM equipo_celular ORDER BY marca, modelo")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $n_serie = $_POST['n_serie'];
    $version = $_POST['version'];
    $compania = $_POST['compania'];
    $es_plan = $_POST['es_plan'];
    $id_celular = $_POST['id_celular'] ?: null;
    $fecha_asignacion = $_POST['fecha_asignacion'];

    // Estado se define según si se asigna celular o no
    $estado = $id_celular ? 'Asignado' : 'Sin asignar';
    if (!$id_celular) {
        $id_celular = null;
    }

    $stmt = $pdo->prepare("UPDATE equipo_cel_sim 
                           SET numero = :numero, n_serie = :n_serie, version = :version, 
                               compania = :compania, es_plan = :es_plan, id_celular = :id_celular, 
                               estado = :estado, fecha_asignacion = :fecha_asignacion
                           WHERE id = :id");
    $stmt->execute([
        'id' => $sim_id,
        'numero' => $numero,
        'n_serie' => $n_serie,
        'version' => $version,
        'compania' => $compania,
        'es_plan' => $es_plan,
        'id_celular' => $id_celular,
        'estado' => $estado,
        'fecha_asignacion' => $fecha_asignacion
    ]);

    header("Location: sims_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar SIM</title>
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css">
    <style>
        .container { max-width: 500px; }
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
                        <?php
                        $companias = ["AT&T", "Movistar", "Bait", "Telcel", "Virgin", "Nextel", "Unefon"];
                        foreach ($companias as $opcion) {
                            $selected = $sim['compania'] === $opcion ? 'selected' : '';
                            echo "<option value=\"$opcion\" $selected>$opcion</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>¿Es Plan?</strong></label>
                    <select name="es_plan" class="form-control" required>
    <option value="">Selecciona</option>
    <option value="Sí" <?= $sim['es_plan'] == 'Sí' ? 'selected' : '' ?>>Sí</option>
    <option value="No" <?= $sim['es_plan'] == 'No' ? 'selected' : '' ?>>No</option>
</select>


                </div>
                <div class="form-group">
                    <label><strong>Celular Asignado:</strong></label>
                    <select name="id_celular" class="form-control">
                        <option value="">- Sin asignar -</option>
                        <?php foreach ($celulares as $cel): ?>
                            <option value="<?= $cel['id'] ?>" <?= $sim['id_celular'] == $cel['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cel['marca'] . ' ' . $cel['modelo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>Fecha de Asignación:</strong></label>
                    <input type="date" name="fecha_asignacion" class="form-control" value="<?= $sim['fecha_asignacion'] ?>" optional>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                    <a href="sims_list.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
