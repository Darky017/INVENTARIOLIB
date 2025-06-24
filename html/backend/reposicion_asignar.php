<?php
require_once '../config.php';
require_once 'header.php';

// Obtener el ID del equipo actual y el usuario
$equipo_actual_id = isset($_GET['equipo_id']) ? (int)$_GET['equipo_id'] : 0;

// Obtener datos del equipo actual y usuario asignado
$stmt = $pdo->prepare("SELECT e.*, u.primer_nombre, u.apellido_paterno, s.nombre AS super_nombre, s.apellido AS super_apellido
                       FROM equipo_computo e
                       LEFT JOIN usuarios u ON e.usuario = u.id
                       LEFT JOIN superusuarios s ON e.usuario = s.id
                       WHERE e.id = ?");
$stmt->execute([$equipo_actual_id]);
$equipo_actual = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener usuario asignado
$usuario_id = $equipo_actual['usuario'] ?? null;
$usuario_nombre = '';
if (!empty($equipo_actual['primer_nombre'])) {
    $usuario_nombre = $equipo_actual['primer_nombre'] . ' ' . $equipo_actual['apellido_paterno'];
} elseif (!empty($equipo_actual['super_nombre'])) {
    $usuario_nombre = $equipo_actual['super_nombre'] . ' ' . $equipo_actual['super_apellido'];
} else {
    $usuario_nombre = 'Sin asignar';
}

// Obtener equipos disponibles (no asignados a ningún usuario)
$stmt = $pdo->query("SELECT id, nombre_pc, marca_pc, serial_pc, modelo_pc FROM equipo_computo WHERE usuario IS NULL OR usuario = ''");
$equipos_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_equipo_id'])) {
    $nuevo_equipo_id = (int)$_POST['nuevo_equipo_id'];
    $usuario_id = $equipo_actual['usuario'];
    $superusuario_id = $_SESSION['superusuario']['id'] ?? null;

    // 1. Desvincular el equipo anterior
    $stmt = $pdo->prepare("UPDATE equipo_computo SET usuario = NULL, asignacion = NULL WHERE id = ?");
    $stmt->execute([$equipo_actual_id]);
    registrar_auditoria($pdo, $superusuario_id, 'reposicion', 'Desvinculó equipo anterior en reposición', 'equipo_computo', $equipo_actual_id);

    // 2. Asignar el nuevo equipo al usuario
    $stmt = $pdo->prepare("UPDATE equipo_computo SET usuario = ?, asignacion = NOW() WHERE id = ?");
    $stmt->execute([$usuario_id, $nuevo_equipo_id]);
    registrar_auditoria($pdo, $superusuario_id, 'reposicion', 'Asignó nuevo equipo en reposición', 'equipo_computo', $nuevo_equipo_id);

    $mensaje = 'Reposición realizada correctamente. El usuario ha sido asignado al nuevo equipo.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reposición y Asignación de Equipo</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendor/select2/css/select2.min.css">
    <style>
        .container { max-width: 700px; margin-top: 40px; }
    </style>
</head>
<body>
<div class="container">
    <h3 class="mb-4">Reposición y Asignación de Equipo</h3>
    <?php if ($mensaje): ?>
        <div class="alert alert-success"> <?= htmlspecialchars($mensaje) ?> </div>
    <?php endif; ?>
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Equipo Actual</div>
        <div class="card-body">
            <p><strong>ID:</strong> <?= htmlspecialchars($equipo_actual['id']) ?></p>
            <p><strong>Nombre PC:</strong> <?= htmlspecialchars($equipo_actual['nombre_pc']) ?></p>
            <p><strong>Usuario Asignado:</strong> <?= htmlspecialchars($usuario_nombre) ?></p>
        </div>
    </div>
    <form method="POST">
        <div class="form-group">
            <label for="nuevo_equipo_id"><strong>Selecciona el nuevo equipo a asignar:</strong></label>
            <select name="nuevo_equipo_id" id="nuevo_equipo_id" class="form-control select2" required>
                <option value="">-- Selecciona un equipo --</option>
                <?php foreach ($equipos_disponibles as $eq): ?>
                    <option value="<?= htmlspecialchars($eq['id']) ?>">
                        <?= htmlspecialchars($eq['nombre_pc'] . ' | ' . $eq['marca_pc'] . ' | ' . $eq['serial_pc'] . ' | ' . $eq['modelo_pc']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Reasignar Equipo</button>
        <a href="reposiciones_equipos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/select2/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>
</body>
</html> 