<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: equipos_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO sistema (equipo_id, version_so, tactil, ip_fija, mac_wifi, mac_ethernet, nombre_equipo_red) 
                           VALUES (:id, :version_so, :tactil, :ip_fija, :mac_wifi, :mac_ethernet, :nombre_equipo_red)");
    $stmt->execute([
        'id' => $id,
        'version_so' => $_POST['version_so'],
        'tactil' => $_POST['tactil'],
        'ip_fija' => $_POST['ip_fija'],
        'mac_wifi' => $_POST['mac_wifi'],
        'mac_ethernet' => $_POST['mac_ethernet'],
        'nombre_equipo_red' => $_POST['nombre_equipo_red']
    ]);

    header("Location: equipos_detalles.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Sistema</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container { max-width: 450px; }
        .card { margin-top: 30px; border-radius: 10px; }
        .form-group { margin-bottom: 10px; }
        .btn { padding: 6px 12px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5>Registrar Sistema</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label><strong>Versión SO:</strong></label>
                    <input type="text" name="version_so" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><strong>¿Táctil?</strong></label>
                    <select name="tactil" class="form-control">
                        <option value="Sí">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>IP Fija:</strong></label>
                    <input type="text" name="ip_fija" class="form-control">
                </div>
              <div class="form-group">
    <label><strong>MAC Wifi:</strong></label>
    <input type="text" name="mac_wifi" class="form-control" id="mac_wifi">
</div>

<script>
document.getElementById('mac_wifi').addEventListener('input', function (e) {
    let value = e.target.value.replace(/[^A-Fa-f0-9]/g, ''); // Elimina caracteres no hexadecimales
    let formattedValue = '';
    for (let i = 0; i < value.length; i += 2) {
        if (i > 0) {
            formattedValue += ':';
        }
        formattedValue += value.substr(i, 2);
        if (i >= 10) { // Detiene después del quinto par
            break;
        }
    }
    e.target.value = formattedValue;
});
</script>

    <label><strong>MAC Ethernet:</strong></label>
    <input type="text" name="mac_ethernet" class="form-control" id="mac_ethernet">
</div>

<script>
document.getElementById('mac_ethernet').addEventListener('input', function (e) {
    let value = e.target.value.replace(/[^A-Fa-f0-9]/g, ''); // Elimina caracteres no hexadecimales
    let formattedValue = '';
    for (let i = 0; i < value.length; i += 2) {
        if (i > 0) {
            formattedValue += ':';
        }
        formattedValue += value.substr(i, 2);
        if (i >= 10) { // Detiene después del quinto par
            break;
        }
    }
    e.target.value = formattedValue;
});
</script>

                <div class="form-group">
                    <label><strong>Nombre del Equipo en Red:</strong></label>
                    <input type="text" name="nombre_equipo_red" class="form-control">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="equipos_detalles.php?id=<?= $id ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
