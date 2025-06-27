<?php
session_start();
require_once '../config.php';
require_once 'header.php';

setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');

function formatearFecha($fecha) {
    if (!$fecha || $fecha == "0000-00-00") {
        return "Sin fecha";
    }
    return strftime("%d %b, %Y", strtotime($fecha)); 
}

$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Parámetros
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$filtro_departamento = isset($_GET['filtro_departamento']) ? $_GET['filtro_departamento'] : '';
$filtro_estado = isset($_GET['filtro_estado']) ? $_GET['filtro_estado'] : '';
$filtro_usuario = isset($_GET['filtro_usuario']) ? $_GET['filtro_usuario'] : '';

// Construir cláusula WHERE
$condiciones = [];
$params = [];

if ($buscar) {
    $condiciones[] = "(impresoras.marca LIKE :buscar OR 
                      impresoras.modelo LIKE :buscar OR 
                      impresoras.numero_serie LIKE :buscar OR 
                      impresoras.departamento LIKE :buscar OR
                      CONCAT(usuarios.primer_nombre, ' ', usuarios.apellido_paterno) LIKE :buscar)";
    $params[':buscar'] = "%$buscar%";
}

if ($filtro_departamento) {
    $condiciones[] = "impresoras.departamento = :departamento";
    $params[':departamento'] = $filtro_departamento;
}

if ($filtro_estado) {
    $condiciones[] = "impresoras.estado = :estado";
    $params[':estado'] = $filtro_estado;
}

if ($filtro_usuario === 'no_asignado') {
    $condiciones[] = "impresoras.usuario_id IS NULL";
} elseif ($filtro_usuario !== '') {
    $condiciones[] = "impresoras.usuario_id = :usuario";
    $params[':usuario'] = $filtro_usuario;
}

$where = count($condiciones) > 0 ? 'WHERE ' . implode(' AND ', $condiciones) : '';

// Total de registros
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM impresoras 
                             LEFT JOIN usuarios ON impresoras.usuario_id = usuarios.id 
                             $where");
foreach ($params as $key => $val) {
    $total_stmt->bindValue($key, $val);
}
$total_stmt->execute();
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Datos paginados
$stmt = $pdo->prepare("
    SELECT impresoras.*, 
           CONCAT(usuarios.primer_nombre, ' ', usuarios.apellido_paterno) AS usuario_asignado
    FROM impresoras
    LEFT JOIN usuarios ON impresoras.usuario_id = usuarios.id
    $where
    ORDER BY impresoras.id DESC
    LIMIT :inicio, :registros
");
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registros', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$impresoras = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Para filtros
$departamentos = $pdo->query("SELECT DISTINCT departamento FROM impresoras WHERE departamento IS NOT NULL AND departamento != ''")->fetchAll(PDO::FETCH_COLUMN);
$estados = $pdo->query("SELECT DISTINCT estado FROM impresoras WHERE estado IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
$usuarios = $pdo->query("SELECT id, primer_nombre, apellido_paterno FROM usuarios ORDER BY primer_nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div><h4 class="mb-3">Lista de Impresoras</h4></div>
                <a href="impresoras_add.php" class="btn btn-primary add-list">
                    <i class="las la-plus mr-2"></i> Agregar Impresora
                </a>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <form method="GET" action="">
                <input type="text" name="buscar" id="searchInput" class="form-control" value="<?= htmlspecialchars($buscar); ?>" placeholder="Buscar por usuario, marca, modelo, serie...">
            </form>
        </div>

        <!-- Filtros -->
        <div class="col-md-12 mb-3">
            <form method="GET" class="form-inline">
                <input type="hidden" name="buscar" value="<?= htmlspecialchars($buscar); ?>">
                
                <select name="filtro_departamento" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Todos los departamentos</option>
                    <?php foreach ($departamentos as $d): ?>
                        <option value="<?= $d ?>" <?= $filtro_departamento === $d ? 'selected' : '' ?>><?= $d ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="filtro_estado" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Todos los estados</option>
                    <?php foreach ($estados as $e): ?>
                        <option value="<?= $e ?>" <?= $filtro_estado === $e ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="filtro_usuario" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Todos los usuarios</option>
                    <option value="no_asignado" <?= $filtro_usuario === 'no_asignado' ? 'selected' : '' ?>>Sin asignar</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= $filtro_usuario == $u['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['primer_nombre'] . ' ' . $u['apellido_paterno']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Tabla -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table table-hover">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data text-center">
                            <th>Usuario Asignado</th>
                            <th>Departamento</th>
                            <th>Fecha Asignación</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Número de Serie</th>
                            <th>Contraseña</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body text-center">
                        <?php foreach ($impresoras as $impresora): ?>
                        <tr>
                            <td><?= !empty($impresora['usuario_asignado']) ? htmlspecialchars($impresora['usuario_asignado']) : 'Sin asignar'; ?></td>
                            <td><?= htmlspecialchars($impresora['departamento'] ?? 'N/A'); ?></td>
                            <td>
                                <?php
                                if (!empty($impresora['fecha_asignacion']) && $impresora['fecha_asignacion'] !== '0000-00-00') {
                                    $fecha = strtotime($impresora['fecha_asignacion']);
                                    $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                                    echo date('d', $fecha) . ' ' . $meses[date('n', $fecha) - 1] . ' ' . date('Y', $fecha);
                                } else {
                                    echo !empty($impresora['usuario_id']) ? 'No Aplica' : 'No Asignado';
                                }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($impresora['marca']); ?></td>
                            <td><?= htmlspecialchars($impresora['modelo']); ?></td>
                            <td><?= htmlspecialchars($impresora['numero_serie']); ?></td>
                            <td>
                                <?= !empty($impresora['contrasena']) ? htmlspecialchars($impresora['contrasena']) : 'N/A'; ?>
                            </td>
                            <td>
                                <span class="badge 
                                    <?= $impresora['estado'] === 'En uso' ? 'badge-success' : 
                                        ($impresora['estado'] === 'En Stock' ? 'badge-info' : 
                                        ($impresora['estado'] === 'En mantenimiento' ? 'badge-warning' : 'badge-secondary')); ?>">
                                    <?= htmlspecialchars($impresora['estado']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="accionesDropdown<?= $impresora['id']; ?>" data-toggle="dropdown">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item text-info" href="impresoras_detalles.php?id=<?= $impresora['id']; ?>">
                                            <i class="fas fa-eye"></i> Ver detalles
                                        </a>
                                        <a class="dropdown-item text-success" href="impresoras_edit.php?id=<?= $impresora['id']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a class="dropdown-item text-danger" href="impresoras_delete.php?id=<?= $impresora['id']; ?>" 
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar esta impresora?')">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="col-lg-12">
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($pagina_actual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?= $pagina_actual - 1 ?>&buscar=<?= urlencode($buscar) ?>&filtro_departamento=<?= urlencode($filtro_departamento) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>&filtro_usuario=<?= urlencode($filtro_usuario) ?>">&laquo;</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= ($i == $pagina_actual) ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>&filtro_departamento=<?= urlencode($filtro_departamento) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>&filtro_usuario=<?= urlencode($filtro_usuario) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pagina_actual < $total_paginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?= $pagina_actual + 1 ?>&buscar=<?= urlencode($buscar) ?>&filtro_departamento=<?= urlencode($filtro_departamento) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>&filtro_usuario=<?= urlencode($filtro_usuario) ?>">&raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?> 