<?php
session_start();
require_once '../config.php';

if (!isset($_GET['id']) || !isset($_GET['equipo_id'])) {
    header("Location: equipos_list.php");
    exit();
}

$cargador_id = $_GET['id'];
$equipo_id = $_GET['equipo_id'];
$errors = [];

// Obtener datos del cargador
$stmt = $pdo->prepare("SELECT * FROM cargador WHERE id = :id");
$stmt->execute(['id' => $cargador_id]);
$cargador = $stmt->fetch();

if (!$cargador) {
    header("Location: equipos_list.php");
    exit();
}

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
        $stmt = $pdo->prepare("UPDATE cargador SET marca = :marca, numero_serie = :numero_serie, modelo = :modelo, watts = :watts WHERE id = :id");
        $stmt->execute(['marca' => $marca, 'numero_serie' => $num_serie, 'modelo' => $modelo, 'watts' => $watts, 'id' => $cargador_id]);

        header("Location: equipos_detalles.php?id=$equipo_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cargador</title>
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
            <h5>Editar Cargador</h5>
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
                    <input type="text" name="marca" id="marca" class="form-control" value="<?= htmlspecialchars($cargador['marca']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="numero_serie"><strong>Número de Serie:</strong></label>
                    <input type="text" name="numero_serie" id="numero_serie" class="form-control" value="<?= htmlspecialchars($cargador['numero_serie']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="modelo"><strong>Modelo:</strong></label>
                    <input type="text" name="modelo" id="modelo" class="form-control" value="<?= htmlspecialchars($cargador['modelo']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="watts"><strong>Watts:</strong></label>
                    <input type="text" name="watts" id="watts" class="form-control" value="<?= htmlspecialchars($cargador['watts']) ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                    <a href="equipos_detalles.php?id=<?= $equipo_id ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
