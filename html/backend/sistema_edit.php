<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM sistema WHERE equipo_id = :id");
$stmt->execute(['id' => $id]);
$sistema = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los valores del formulario
    $version_so = $_POST['version_so'];
    $tactil = $_POST['tactil'];
    $ip_fija = $_POST['ip_fija'];
    $mac_wifi = $_POST['mac_wifi'];
    $mac_ethernet = $_POST['mac_ethernet'];
    $nombre_equipo_red = $_POST['nombre_equipo_red'];

    // Validaciones (si se requieren)
    $errors = [];
    if (empty($version_so) || empty($tactil) || empty($mac_wifi) || empty($mac_ethernet) || empty($nombre_equipo_red)) {
        $errors[] = 'Todos los campos son obligatorios.';
    }

    // Si no hay errores, actualizamos la base de datos
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE sistema SET version_so = :version_so, tactil = :tactil, ip_fija = :ip_fija, mac_wifi = :mac_wifi, mac_ethernet = :mac_ethernet, nombre_equipo_red = :nombre_equipo_red WHERE equipo_id = :id");
        $stmt->execute([
            'version_so' => $version_so,
            'tactil' => $tactil,
            'ip_fija' => $ip_fija,
            'mac_wifi' => $mac_wifi,
            'mac_ethernet' => $mac_ethernet,
            'nombre_equipo_red' => $nombre_equipo_red,
            'id' => $id
        ]);

        header("Location: equipos_detalles.php?id=$id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Sistema</title>
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
            <h5>Editar Sistema</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label><strong>Versión SO:</strong></label>
                    <input type="text" name="version_so" class="form-control" value="<?= htmlspecialchars($sistema['version_so']) ?>" required>
                </div>
                <div class="form-group">
                    <label><strong>¿Táctil?:</strong></label>
                    <select name="tactil" class="form-control" required>
                        <option value="Sí" <?= $sistema['tactil'] == 'Sí' ? 'selected' : ''; ?>>Sí</option>
                        <option value="No" <?= $sistema['tactil'] == 'No' ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>IP Fija:</strong></label>
                    <input type="text" name="ip_fija" class="form-control" value="<?= htmlspecialchars($sistema['ip_fija']) ?>" optional>
                </div>
                <div class="form-group">
                    <label><strong>MAC WiFi:</strong></label>
                    <input type="text" name="mac_wifi" class="form-control" value="<?= htmlspecialchars($sistema['mac_wifi']) ?>" required>
                </div>
                <div class="form-group">
                    <label><strong>MAC Ethernet:</strong></label>
                    <input type="text" name="mac_ethernet" class="form-control" value="<?= htmlspecialchars($sistema['mac_ethernet']) ?>" required>
                </div>
                <div class="form-group">
                    <label><strong>Nombre en Red:</strong></label>
                    <input type="text" name="nombre_equipo_red" class="form-control" value="<?= htmlspecialchars($sistema['nombre_equipo_red']) ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="equipos_detalles.php?id=<?= $id ?>" class="btn btn-secondary mt-3">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
