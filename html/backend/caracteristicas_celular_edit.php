<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: celulares_list.php");
    exit();
}

// Obtener las características actuales
$stmt = $pdo->prepare("SELECT * FROM caracteristicas_celular WHERE id_celular = ?");
$stmt->execute([$id]);
$caracteristicas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$caracteristicas) {
    die("No se encontraron características para este celular.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE caracteristicas_celular SET ram = :ram, almacenamiento = :almacenamiento, procesador = :procesador WHERE id_celular = :id_celular");
    $stmt->execute([
        'ram' => $_POST['ram'],
        'almacenamiento' => $_POST['almacenamiento'],
        'procesador' => $_POST['procesador'],
        'id_celular' => $id
    ]);

    header("Location: celulares_detalles.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Características</title>
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-warning text-white text-center">
            <h5>Editar Características</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>RAM:</label>
                    <select name="ram" class="form-control" required>
                        <?php
                        $ram_options = [4, 6, 8, 12, 16];
                        foreach ($ram_options as $ram) {
                            $selected = ($caracteristicas['ram'] == $ram) ? 'selected' : '';
                            echo "<option value=\"$ram\" $selected>$ram GB</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Almacenamiento:</label>
                    <select name="almacenamiento" class="form-control" required>
                        <?php
                        $almacenamiento_options = [32, 64, 128, 256, 512, '1tb'];
                        foreach ($almacenamiento_options as $alm) {
                            $selected = ($caracteristicas['almacenamiento'] == $alm) ? 'selected' : '';
                            $label = ($alm === '1tb') ? '1 TB' : "$alm GB";
                            echo "<option value=\"$alm\" $selected>$label</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Procesador:</label>
                    <input type="text" name="procesador" class="form-control" value="<?= htmlspecialchars($caracteristicas['procesador']) ?>" required>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="celulares_detalles.php?id=<?= $id ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
