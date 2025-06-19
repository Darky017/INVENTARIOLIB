<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: celulares_list.php");
    exit();
}

// Obtener la información actual de la eSIM
$stmt = $pdo->prepare("SELECT * FROM esim WHERE id = :id");
$stmt->execute(['id' => $id]);
$esim = $stmt->fetch();

if (!$esim) {
    header("Location: celulares_list.php");
    exit();
}

$id_celular = $esim['id_celular'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE esim SET numero = :numero, n_serie = :n_serie, compania = :compania, es_plan = :es_plan, fecha_asignacion = :fecha_asignacion WHERE id = :id");
    $stmt->execute([
        'numero' => $_POST['numero'],
        'n_serie' => $_POST['n_serie'],
        'compania' => $_POST['compania'],
        'es_plan' => $_POST['es_plan'],
        'fecha_asignacion' => $_POST['fecha_asignacion'],
        'id' => $id
    ]);

    header("Location: celulares_detalles.php?id=$id_celular");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar eSIM</title>
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark text-center">
            <h5>Editar eSIM</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Número:</label>
                    <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($esim['numero']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Número de Serie:</label>
                    <input type="text" name="n_serie" class="form-control" value="<?= htmlspecialchars($esim['n_serie']) ?>" required>
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
                    <label>¿Es Plan?</label>
                    <select name="es_plan" class="form-control" required>
                        <option value="1" <?= $esim['es_plan'] ? 'selected' : '' ?>>Sí</option>
                        <option value="0" <?= !$esim['es_plan'] ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha de Asignación:</label>
                    <input type="date" name="fecha_asignacion" class="form-control" value="<?= htmlspecialchars($esim['fecha_asignacion']) ?>" required>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a href="celulares_detalles.php?id=<?= $id_celular ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
