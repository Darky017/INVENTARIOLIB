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

// Verificar qué tablas de equipos existen
$tablas_existentes = [
    'equipo_computo' => tabla_existe($pdo, 'equipo_computo'),
    'equipo_celular' => tabla_existe($pdo, 'equipo_celular'),
    'equipo_tablet' => tabla_existe($pdo, 'equipo_tablet'),
    'equipo_tv' => tabla_existe($pdo, 'equipo_tv'),
    'equipo_periferico' => tabla_existe($pdo, 'equipo_periferico'),
    'equipo_impresora' => tabla_existe($pdo, 'equipo_impresora')
];

// Parámetros de filtro
$tipo_equipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$equipo_id = isset($_GET['equipo_id']) ? (int)$_GET['equipo_id'] : 0;
$usuario_id = isset($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : 0;
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Paginación
$registros_por_pagina = 20;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Construir consulta con filtros
$where_conditions = [];
$params = [];

if ($tipo_equipo) {
    $where_conditions[] = "ha.tipo_equipo = :tipo_equipo";
    $params[':tipo_equipo'] = $tipo_equipo;
}

if ($equipo_id) {
    $where_conditions[] = "ha.equipo_id = :equipo_id";
    $params[':equipo_id'] = $equipo_id;
}

if ($usuario_id) {
    $where_conditions[] = "ha.usuario_id = :usuario_id";
    $params[':usuario_id'] = $usuario_id;
}

if ($fecha_inicio) {
    $where_conditions[] = "DATE(ha.fecha_asignacion) >= :fecha_inicio";
    $params[':fecha_inicio'] = $fecha_inicio;
}

if ($fecha_fin) {
    $where_conditions[] = "DATE(ha.fecha_asignacion) <= :fecha_fin";
    $params[':fecha_fin'] = $fecha_fin;
}

$where_clause = count($where_conditions) > 0 ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Consulta para total de registros
$total_sql = "
    SELECT COUNT(*) 
    FROM historial_asignaciones ha
    LEFT JOIN usuarios u ON ha.usuario_id = u.id
    LEFT JOIN superusuarios s ON ha.usuario_id = s.id
    $where_clause
";

$total_stmt = $pdo->prepare($total_sql);
foreach ($params as $key => $value) {
    $total_stmt->bindValue($key, $value);
}
$total_stmt->execute();
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Construir la consulta principal dinámicamente
$joins = [];
$case_equipo_nombre = [];
$case_equipo_serie = [];

// Agregar JOINs solo para tablas que existen
if ($tablas_existentes['equipo_computo']) {
    $joins[] = "LEFT JOIN equipo_computo ec ON ha.equipo_id = ec.id AND ha.tipo_equipo = 'computo'";
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'computo' THEN ec.nombre_pc";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'computo' THEN ec.serial_pc";
}

if ($tablas_existentes['equipo_celular']) {
    $joins[] = "LEFT JOIN equipo_celular cel ON ha.equipo_id = cel.id AND ha.tipo_equipo = 'celular'";
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'celular' THEN CONCAT(cel.marca, ' ', cel.modelo)";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'celular' THEN cel.n_serie";
}

// Para tablas que pueden no existir, usar subconsultas
if ($tablas_existentes['equipo_tablet']) {
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'tablet' THEN (SELECT CONCAT(marca, ' ', modelo) FROM equipo_tablet WHERE id = ha.equipo_id LIMIT 1)";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'tablet' THEN (SELECT n_serie FROM equipo_tablet WHERE id = ha.equipo_id LIMIT 1)";
} else {
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'tablet' THEN 'Tabla no disponible'";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'tablet' THEN 'N/A'";
}

if ($tablas_existentes['equipo_tv']) {
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'tv' THEN (SELECT CONCAT(marca, ' ', modelo) FROM equipo_tv WHERE id = ha.equipo_id LIMIT 1)";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'tv' THEN (SELECT n_serie FROM equipo_tv WHERE id = ha.equipo_id LIMIT 1)";
} else {
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'tv' THEN 'Tabla no disponible'";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'tv' THEN 'N/A'";
}

if ($tablas_existentes['equipo_periferico']) {
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'periferico' THEN (SELECT CONCAT(marca, ' ', modelo) FROM equipo_periferico WHERE id = ha.equipo_id LIMIT 1)";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'periferico' THEN (SELECT n_serie FROM equipo_periferico WHERE id = ha.equipo_id LIMIT 1)";
} else {
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'periferico' THEN 'Tabla no disponible'";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'periferico' THEN 'N/A'";
}

if ($tablas_existentes['equipo_impresora']) {
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'impresora' THEN (SELECT CONCAT(marca, ' ', modelo) FROM equipo_impresora WHERE id = ha.equipo_id LIMIT 1)";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'impresora' THEN (SELECT n_serie FROM equipo_impresora WHERE id = ha.equipo_id LIMIT 1)";
} else {
    $case_equipo_nombre[] = "WHEN ha.tipo_equipo = 'impresora' THEN 'Tabla no disponible'";
    $case_equipo_serie[] = "WHEN ha.tipo_equipo = 'impresora' THEN 'N/A'";
}

// Construir la consulta SQL
$sql = "
    SELECT 
        ha.*,
        u.primer_nombre, u.apellido_paterno,
        s.nombre AS super_nombre, s.apellido AS super_apellido,
        sa.nombre AS asignador_nombre, sa.apellido AS asignador_apellido,
        CASE " . implode(' ', $case_equipo_nombre) . " ELSE 'Equipo no encontrado' END AS equipo_nombre,
        CASE " . implode(' ', $case_equipo_serie) . " ELSE 'N/A' END AS equipo_serie
    FROM historial_asignaciones ha
    LEFT JOIN usuarios u ON ha.usuario_id = u.id
    LEFT JOIN superusuarios s ON ha.usuario_id = s.id
    LEFT JOIN superusuarios sa ON ha.usuario_que_asigno = sa.id
    " . implode(' ', $joins) . "
    $where_clause
    ORDER BY ha.fecha_asignacion DESC
    LIMIT :inicio, :registros
";

$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registros', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener listas para filtros (solo tipos de equipos que existen)
$tipos_equipo = [];
if ($tablas_existentes['equipo_computo']) $tipos_equipo[] = 'computo';
if ($tablas_existentes['equipo_celular']) $tipos_equipo[] = 'celular';
if ($tablas_existentes['equipo_tablet']) $tipos_equipo[] = 'tablet';
if ($tablas_existentes['equipo_tv']) $tipos_equipo[] = 'tv';
if ($tablas_existentes['equipo_periferico']) $tipos_equipo[] = 'periferico';
if ($tablas_existentes['equipo_impresora']) $tipos_equipo[] = 'impresora';

$usuarios = $pdo->query("SELECT id, primer_nombre, apellido_paterno FROM usuarios ORDER BY primer_nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Asignaciones - Dashboard Grupo Libera</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendor/select2/css/select2.min.css">
    <style>
        .container-fluid { max-width: 1400px; }
        .card { border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .table th { background-color: #f8f9fa; font-weight: 600; }
        .badge-asignacion { background-color: #28a745; }
        .badge-desasignacion { background-color: #dc3545; }
        .filtros { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .estado-activo { color: #28a745; font-weight: bold; }
        .estado-inactivo { color: #6c757d; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-history"></i> Historial de Asignaciones de Equipos
                    </h4>
                </div>
                <div class="card-body">
                    
                    <!-- Filtros -->
                    <div class="filtros">
                        <form method="GET" class="row">
                            <div class="col-md-2">
                                <label><strong>Tipo de Equipo:</strong></label>
                                <select name="tipo" class="form-control">
                                    <option value="">Todos</option>
                                    <?php foreach ($tipos_equipo as $tipo): ?>
                                        <option value="<?= $tipo ?>" <?= $tipo_equipo === $tipo ? 'selected' : '' ?>>
                                            <?= ucfirst($tipo) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label><strong>Usuario:</strong></label>
                                <select name="usuario_id" class="form-control">
                                    <option value="">Todos</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= $usuario['id'] ?>" <?= $usuario_id == $usuario['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario['primer_nombre'] . ' ' . $usuario['apellido_paterno']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label><strong>Fecha Inicio:</strong></label>
                                <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($fecha_inicio) ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label><strong>Fecha Fin:</strong></label>
                                <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($fecha_fin) ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label><strong>ID Equipo:</strong></label>
                                <input type="number" name="equipo_id" class="form-control" value="<?= $equipo_id ?>" placeholder="ID del equipo">
                            </div>
                            
                            <div class="col-md-2">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Estadísticas -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Total Registros</h5>
                                    <h3><?= number_format($total_registros) ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Asignaciones Activas</h5>
                                    <h3><?= number_format(array_sum(array_column(array_filter($historial, function($h) { return $h['fecha_desasignacion'] === null; }), 'id'))) ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Página Actual</h5>
                                    <h3><?= $pagina_actual ?> de <?= $total_paginas ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Registros por Página</h5>
                                    <h3><?= $registros_por_pagina ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Historial -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Equipo</th>
                                    <th>Usuario Asignado</th>
                                    <th>Departamento</th>
                                    <th>Fecha Asignación</th>
                                    <th>Fecha Desasignación</th>
                                    <th>Estado</th>
                                    <th>Asignado Por</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historial as $registro): ?>
                                    <tr>
                                        <td>
                                            <strong><?= $registro['equipo_id'] ?></strong>
                                            <br><small class="text-muted">#<?= $registro['id'] ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= ucfirst($registro['tipo_equipo']) ?></span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($registro['equipo_nombre'] ?? 'N/A') ?></strong>
                                            <br><small class="text-muted"><?= htmlspecialchars($registro['equipo_serie'] ?? 'Sin serie') ?></small>
                                        </td>
                                        <td>
                                            <?= obtener_nombre_usuario_asignado($registro) ?>
                                        </td>
                                        <td><?= htmlspecialchars($registro['departamento'] ?? 'N/A') ?></td>
                                        <td>
                                            <span class="estado-activo">
                                                <?= formatear_fecha_historial($registro['fecha_asignacion']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($registro['fecha_desasignacion']): ?>
                                                <span class="estado-inactivo">
                                                    <?= formatear_fecha_historial($registro['fecha_desasignacion']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Activo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($registro['fecha_desasignacion']): ?>
                                                <span class="badge badge-danger">Desasignado</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Asignado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($registro['asignador_nombre']): ?>
                                                <?= htmlspecialchars($registro['asignador_nombre'] . ' ' . $registro['asignador_apellido']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Sistema</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($registro['notas']): ?>
                                                <small class="text-muted"><?= htmlspecialchars(substr($registro['notas'], 0, 50)) ?><?= strlen($registro['notas']) > 50 ? '...' : '' ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">Sin notas</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <?php if ($total_paginas > 1): ?>
                        <nav aria-label="Paginación del historial">
                            <ul class="pagination justify-content-center">
                                <?php if ($pagina_actual > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual - 1])) ?>">
                                            <i class="fas fa-chevron-left"></i> Anterior
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $pagina_actual - 2); $i <= min($total_paginas, $pagina_actual + 2); $i++): ?>
                                    <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($pagina_actual < $total_paginas): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual + 1])) ?>">
                                            Siguiente <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/select2/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('select').select2({
        placeholder: "Seleccionar...",
        allowClear: true
    });
});
</script>

</body>
</html> 