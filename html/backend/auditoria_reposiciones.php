<?php
require_once '../config.php';
require_once 'header.php';

// Consulta para obtener solo auditorías de reposiciones de equipos
$sql = "SELECT a.*, 
               u.primer_nombre, u.apellido_paterno, 
               s.nombre AS super_nombre, s.apellido AS super_apellido
        FROM auditoria a
        LEFT JOIN usuarios u ON a.usuario_id = u.id
        LEFT JOIN superusuarios s ON a.usuario_id = s.id
        WHERE a.accion = 'reposicion'
        ORDER BY a.fecha DESC LIMIT 100";
$stmt = $pdo->query($sql);
$auditorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Auditorías de Reposiciones de Equipos</title>
    <link rel='stylesheet' href='../assets/css/backend-plugin.min.css'>
    <link rel='stylesheet' href='../assets/css/backend.css?v=1.0.0'>
    <link rel='stylesheet' href='../assets/vendor/bootstrap/css/bootstrap.min.css'>
    <style>
        .container { max-width: 1100px; margin-top: 40px; }
        .table th, .table td { font-size: 14px; }
    </style>
</head>
<body>
<div class='container'>
    <h3 class='mb-4'>Auditorías de Reposiciones de Equipos</h3>
    <div class='table-responsive'>
        <table class='table table-bordered table-striped table-hover'>
            <thead class='thead-dark'>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>Tabla</th>
                    <th>ID Registro</th>
                    <th>Fecha</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditorias as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['id']) ?></td>
                        <td>
                            <?php
                                if (!empty($a['primer_nombre'])) {
                                    echo htmlspecialchars($a['primer_nombre'] . ' ' . $a['apellido_paterno']);
                                } elseif (!empty($a['super_nombre'])) {
                                    echo htmlspecialchars($a['super_nombre'] . ' ' . $a['super_apellido']);
                                } else {
                                    echo 'Desconocido';
                                }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($a['accion']) ?></td>
                        <td><?= htmlspecialchars($a['descripcion']) ?></td>
                        <td><?= htmlspecialchars($a['tabla_afectada']) ?></td>
                        <td><?= htmlspecialchars($a['id_registro_afectado']) ?></td>
                        <td><?= htmlspecialchars($a['fecha']) ?></td>
                        <td><?= htmlspecialchars($a['ip_usuario']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html> 