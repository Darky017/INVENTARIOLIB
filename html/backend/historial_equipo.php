<?php
session_start();
require_once '../config.php';
require_once 'header.php';
require_once 'funciones_historial.php';

// Función para verificar si una tabla existe
function tabla_existe($pdo, $tabla) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$tabla'");
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

$equipo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$tipo_equipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'computo';

if (!$equipo_id) {
    header("Location: equipos_list.php");
    exit();
}

// Obtener información del equipo
$equipo_info = null;
switch ($tipo_equipo) {
    case 'computo':
        if (tabla_existe($pdo, 'equipo_computo')) {
            $stmt = $pdo->prepare("SELECT * FROM equipo_computo WHERE id = ?");
            $stmt->execute([$equipo_id]);
            $equipo_info = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        break;
    case 'celular':
        if (tabla_existe($pdo, 'equipo_celular')) {
            $stmt = $pdo->prepare("SELECT * FROM equipo_celular WHERE id = ?");
            $stmt->execute([$equipo_id]);
            $equipo_info = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        break;
    case 'tablet':
        if (tabla_existe($pdo, 'equipo_tablet')) {
            $stmt = $pdo->prepare("SELECT * FROM equipo_tablet WHERE id = ?");
            $stmt->execute([$equipo_id]);
            $equipo_info = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        break;
    case 'tv':
        if (tabla_existe($pdo, 'equipo_tv')) {
            $stmt = $pdo->prepare("SELECT * FROM equipo_tv WHERE id = ?");
            $stmt->execute([$equipo_id]);
            $equipo_info = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        break;
    case 'periferico':
        if (tabla_existe($pdo, 'equipo_periferico')) {
            $stmt = $pdo->prepare("SELECT * FROM equipo_periferico WHERE id = ?");
            $stmt->execute([$equipo_id]);
            $equipo_info = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        break;
    case 'impresora':
        if (tabla_existe($pdo, 'equipo_impresora')) {
            $stmt = $pdo->prepare("SELECT * FROM equipo_impresora WHERE id = ?");
            $stmt->execute([$equipo_id]);
            $equipo_info = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        break;
}

if (!$equipo_info) {
    header("Location: equipos_list.php");
    exit();
}

// Obtener historial del equipo
$historial = obtener_historial_equipo($pdo, $equipo_id, $tipo_equipo);

// Obtener información del usuario actual asignado
$usuario_actual = null;
if (!empty($equipo_info['usuario']) || !empty($equipo_info['id_usuario'])) {
    $usuario_id = $equipo_info['usuario'] ?? $equipo_info['id_usuario'];
    $stmt = $pdo->prepare("SELECT id, primer_nombre, apellido_paterno FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario_actual = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial del Equipo - Dashboard Grupo Libera</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container-fluid { max-width: 1200px; }
        .card { border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .equipo-info { background-color: #f8f9fa; padding: 20px; border-radius: 8px; }
        .historial-item { border-left: 4px solid #007bff; padding-left: 15px; margin-bottom: 15px; }
        .historial-item.desasignacion { border-left-color: #dc3545; }
        .historial-item.asignacion { border-left-color: #28a745; }
        .timeline { position: relative; }
        .timeline::before { content: ''; position: absolute; left: 20px; top: 0; bottom: 0; width: 2px; background: #dee2e6; }
        .timeline-item { position: relative; padding-left: 50px; margin-bottom: 20px; }
        .timeline-item::before { content: ''; position: absolute; left: 15px; top: 10px; width: 10px; height: 10px; border-radius: 50%; background: #007bff; }
        .timeline-item.asignacion::before { background: #28a745; }
        .timeline-item.desasignacion::before { background: #dc3545; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Información del Equipo -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-desktop"></i> Historial del Equipo #<?= $equipo_id ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="equipo-info">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Información del Equipo</h5>
                                <p><strong>Tipo:</strong> <?= ucfirst($tipo_equipo) ?></p>
                                <?php if ($tipo_equipo == 'computo'): ?>
                                    <p><strong>Nombre:</strong> <?= htmlspecialchars($equipo_info['nombre_pc']) ?></p>
                                    <p><strong>Marca:</strong> <?= htmlspecialchars($equipo_info['marca_pc']) ?></p>
                                    <p><strong>Modelo:</strong> <?= htmlspecialchars($equipo_info['modelo_pc']) ?></p>
                                    <p><strong>Serial:</strong> <?= htmlspecialchars($equipo_info['serial_pc']) ?></p>
                                    <p><strong>Estado:</strong> <span class="badge badge-info"><?= htmlspecialchars($equipo_info['estado_pc']) ?></span></p>
                                <?php elseif ($tipo_equipo == 'celular'): ?>
                                    <p><strong>Marca:</strong> <?= htmlspecialchars($equipo_info['marca']) ?></p>
                                    <p><strong>Modelo:</strong> <?= htmlspecialchars($equipo_info['modelo']) ?></p>
                                    <p><strong>Serial:</strong> <?= htmlspecialchars($equipo_info['n_serie']) ?></p>
                                    <p><strong>Estado:</strong> <span class="badge badge-info"><?= htmlspecialchars($equipo_info['estado']) ?></span></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h5>Asignación Actual</h5>
                                <?php if ($usuario_actual): ?>
                                    <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario_actual['primer_nombre'] . ' ' . $usuario_actual['apellido_paterno']) ?></p>
                                    <p><strong>ID Usuario:</strong> <?= $usuario_actual['id'] ?></p>
                                    <?php if ($tipo_equipo == 'computo' && !empty($equipo_info['departamento'])): ?>
                                        <p><strong>Departamento:</strong> <?= htmlspecialchars($equipo_info['departamento']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($equipo_info['asignacion']) || !empty($equipo_info['fecha_asignacion'])): ?>
                                        <p><strong>Fecha Asignación:</strong> <?= formatear_fecha_historial($equipo_info['asignacion'] ?? $equipo_info['fecha_asignacion']) ?></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p><span class="badge badge-warning">Sin asignar</span></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Asignaciones -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i> Historial de Asignaciones
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($historial)): ?>
                        <div class="text-center text-muted">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <h5>No hay historial de asignaciones para este equipo</h5>
                            <p>Este equipo no ha tenido asignaciones registradas en el sistema.</p>
                        </div>
                    <?php else: ?>
                        <div class="timeline">
                            <?php foreach ($historial as $registro): ?>
                                <div class="timeline-item <?= $registro['fecha_desasignacion'] ? 'desasignacion' : 'asignacion' ?>">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Fecha:</strong><br>
                                                    <?= formatear_fecha_historial($registro['fecha_asignacion']) ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Usuario:</strong><br>
                                                    <?= obtener_nombre_usuario_asignado($registro) ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Estado:</strong><br>
                                                    <?php if ($registro['fecha_desasignacion']): ?>
                                                        <span class="badge badge-danger">Desasignado</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-success">Asignado</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Departamento:</strong><br>
                                                    <?= htmlspecialchars($registro['departamento'] ?? 'N/A') ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Asignado por:</strong><br>
                                                    <?php if ($registro['asignador_nombre']): ?>
                                                        <?= htmlspecialchars($registro['asignador_nombre'] . ' ' . $registro['asignador_apellido']) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sistema</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <?php if ($registro['fecha_desasignacion']): ?>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Fecha Desasignación:</strong><br>
                                                        <?= formatear_fecha_historial($registro['fecha_desasignacion']) ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Duración:</strong><br>
                                                        <?php
                                                        $inicio = new DateTime($registro['fecha_asignacion']);
                                                        $fin = new DateTime($registro['fecha_desasignacion']);
                                                        $duracion = $inicio->diff($fin);
                                                        echo $duracion->days . ' días, ' . $duracion->h . ' horas';
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($registro['notas']): ?>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <strong>Notas:</strong><br>
                                                        <small class="text-muted"><?= htmlspecialchars($registro['notas']) ?></small>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="text-center">
                <a href="historial_asignaciones.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Historial General
                </a>
                <?php if ($tipo_equipo == 'computo'): ?>
                    <a href="equipos_edit.php?id=<?= $equipo_id ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar Equipo
                    </a>
                <?php elseif ($tipo_equipo == 'celular'): ?>
                    <a href="celulares_edit.php?id=<?= $equipo_id ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar Celular
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html> 