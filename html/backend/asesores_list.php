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

// Contadores de asesores
$total_general_stmt = $pdo->query("SELECT COUNT(*) FROM asesores");
$total_general = $total_general_stmt->fetchColumn();

$total_activos_stmt = $pdo->query("SELECT COUNT(*) FROM asesores WHERE estado = 'Activo'");
$total_activos = $total_activos_stmt->fetchColumn();

$total_suspendidos_stmt = $pdo->query("SELECT COUNT(*) FROM asesores WHERE estado = 'Suspendido'");
$total_suspendidos = $total_suspendidos_stmt->fetchColumn();

$total_eliminados_stmt = $pdo->query("SELECT COUNT(*) FROM asesores WHERE estado = 'Eliminado'");
$total_eliminados = $total_eliminados_stmt->fetchColumn();


// Filtros de búsqueda
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';
$zona = isset($_GET['zona']) ? $_GET['zona'] : '';

$where = "WHERE 1";
$params = [];

if (!empty($busqueda)) {
    $where .= " AND (primer_nombre LIKE :busqueda OR segundo_nombre LIKE :busqueda OR primer_apellido LIKE :busqueda OR segundo_apellido LIKE :busqueda OR correo_corporativo LIKE :busqueda)";
    $params[':busqueda'] = "%$busqueda%";
}

if (!empty($estado)) {
    $where .= " AND estado = :estado";
    $params[':estado'] = $estado;
}
if (!empty($zona)) {
    $where .= " AND zona = :zona";
    $params[':zona'] = $zona;
}

$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM asesores $where");
$total_stmt->execute($params);
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

$stmt = $pdo->prepare("SELECT * FROM asesores $where ORDER BY id DESC LIMIT :inicio, :registros");
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registros', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$asesores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body> 
<script>
let tiempoInactivo = 300000; // 5 minutos
function cerrarSesion() {
    window.location.href = 'auth-sign-in.php';
}
let temporizador = setTimeout(cerrarSesion, tiempoInactivo);
function reiniciarTemporizador() {
    clearTimeout(temporizador);
    temporizador = setTimeout(cerrarSesion, tiempoInactivo);
}
document.addEventListener("mousemove", reiniciarTemporizador);
document.addEventListener("keydown", reiniciarTemporizador);
</script>
</body>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Lista de Asesores</h4>
                </div>
                


                <a href="asesores_add.php" class="btn btn-primary add-list">
                    <i class="las la-plus mr-3"></i>Agregar Asesor
                </a>
            </div>
        </div>

<div class="d-flex justify-content-between align-items-center mb-3 px-2 flex-wrap">
    <div class="mb-2 mb-md-0">
        <span class="mr-3"><strong>Total:</strong> <?= $total_general ?></span>
        <span class="mr-3 text-success"><strong>Activos:</strong> <?= $total_activos ?></span>
        <span class="mr-3 text-warning"><strong>Suspendidos:</strong> <?= $total_suspendidos ?></span>
        <span class="text-danger"><strong>Eliminados:</strong> <?= $total_eliminados ?></span>
    </div>
    <div class="ml-md-3 mt-2 mt-md-0">
        <a href="asesores_export.php" class="btn btn-secondary">
            <i class="las la-file-export mr-1"></i>Exportar  
        </a>
    </div>
</div>

        <!-- Filtros -->
        <div class="col-lg-12 mb-3">
            <form method="get" class="form-inline">
                <input type="text" name="busqueda" value="<?= htmlspecialchars($busqueda) ?>" class="form-control mr-2" placeholder="Buscar por nombre, apellido o correo corporativo">
                <select name="estado" class="form-control mr-2">
                    <option value="">Todos los estados</option>
                    <option value="Activo" <?= ($estado == 'Activo') ? 'selected' : '' ?>>Activo</option>
                    <option value="Suspendido" <?= ($estado == 'Suspendido') ? 'selected' : '' ?>>Suspendido</option>
                    <option value="Inactivo" <?= ($estado == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                </select>
                <select name="zona" class="form-control mr-2">
                    <option value="">Todas las zonas</option>
                    <option value="Mérida" <?= ($zona == 'Mérida') ? 'selected' : '' ?>>Mérida</option>
                    <option value="Mérida 2" <?= ($zona == 'Mérida 2') ? 'selected' : '' ?>>Mérida 2</option>
                    <option value="Ciudad de México" <?= ($zona == 'Ciudad de México') ? 'selected' : '' ?>>Ciudad de México</option>
                    <option value="Estado de México" <?= ($zona == 'Estado de México') ? 'selected' : '' ?>>Estado de México</option>
                    <option value="Bajío" <?= ($zona == 'Bajío') ? 'selected' : '' ?>>Bajío</option>
                    <option value="Guadalajara" <?= ($zona == 'Guadalajara') ? 'selected' : '' ?>>Guadalajara</option>
                    <option value="Monterrey" <?= ($zona == 'Monterrey') ? 'selected' : '' ?>>Monterrey</option>
                    <option value="Real State" <?= ($zona == 'Real State') ? 'selected' : '' ?>>Real State</option>
                    <option value="Caribe" <?= ($zona == 'Caribe') ? 'selected' : '' ?>>Caribe</option>
                    <option value="Santuario" <?= ($zona == 'Santuario') ? 'selected' : '' ?>>Santuario</option>
                    <option value="Vertere" <?= ($zona == 'Vertere') ? 'selected' : '' ?>>Vertere</option>
                </select>
                <button type="button" class="btn btn-secondary mr-2" onclick="window.location.href='asesores_list.php'">Limpiar</button>
                <button type="submit" class="btn btn-secondary">Filtrar</button>
            </form>
        </div>

        <!-- Tabla -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data text-center">
                            <th colspan="7" class="bg-secondary text-white">Datos del Asesor</th>
                            <th colspan="3" class="bg-info text-white">Titan</th>
                            <th colspan="3" class="bg-warning text-dark">Baja de Cuenta Titan</th>
                            <th colspan="3" class="bg-danger text-white">Eliminación Cuenta Titan</th>
                            <th rowspan="2" class="bg-dark text-white">Acciones</th>
                        </tr>
                        <tr class="ligth ligth-data">
                            <th>Primer Nombre</th>
                            <th>Segundo Nombre</th>
                            <th>Primer Apellido</th>
                            <th>Segundo Apellido</th>
                            <th>Correo Personal</th>
                            <th>Num. Personal</th>
                            <th>Zona</th>
                            <th>Correo Corporativo</th>
                            <th>Contraseña</th>
                            <th>Estado</th>
                            <th>Fecha Solicitud Baja</th>
                            <th>Fecha Suspensión</th>
                            <th>Fecha Próxima Eliminación</th>
                            <th>Fecha Respaldo</th>
                            <th>Fecha Eliminación</th>
                            <th>Comentarios</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body text-center">
                        <?php foreach ($asesores as $asesor): ?>
                        <tr>
                            <td><?= htmlspecialchars($asesor['primer_nombre']); ?></td>
                            <td><?= !empty($asesor['segundo_nombre']) ? htmlspecialchars($asesor['segundo_nombre']) : 'No Aplica'; ?></td>
                            <td><?= htmlspecialchars($asesor['primer_apellido']); ?></td>
                            <td><?= htmlspecialchars($asesor['segundo_apellido']); ?></td>
                            <td><?= !empty($asesor['correo_personal']) ? htmlspecialchars($asesor['correo_personal']) : 'No Aplica'; ?></td>
                            <td><?= !empty($asesor['n_tel']) ? htmlspecialchars($asesor['n_tel']) : 'No Aplica'; ?></td>
                            <td><?= htmlspecialchars($asesor['zona']); ?></td>
                            <td><?= htmlspecialchars($asesor['correo_corporativo']); ?></td>
                            <td><?= htmlspecialchars($asesor['contrasena']); ?></td>
                            <td><span class="badge <?= ($asesor['estado'] == 'Activo') ? 'badge-success' : (($asesor['estado'] == 'Suspendido') ? 'badge-warning' : 'badge-danger'); ?>"><?= htmlspecialchars($asesor['estado']); ?></span></td>
                            <td><?= formatearFecha($asesor['solicitud_baja']); ?></td>
                            <td><?= formatearFecha($asesor['fecha_baja']); ?></td>
                            <td><?= formatearFecha($asesor['fecha_proxima_eliminacion']); ?></td>
                            <td><?= formatearFecha($asesor['fecha_respaldo']); ?></td>
                            <td><?= formatearFecha($asesor['fecha_eliminacion']); ?></td>
                            <td><?= htmlspecialchars($asesor['comentarios']); ?></td>
                            <td>
                                <div class="dropdown text-center">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="accionesAsesor<?= $asesor['id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="accionesAsesor<?= $asesor['id']; ?>">
                                        <a class="dropdown-item text-success" href="asesores_edit.php?id=<?= $asesor['id']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a class="dropdown-item text-warning" href="asesores_estado.php?id=<?= $asesor['id']; ?>">
                                            <i class="fas fa-sync-alt"></i> Cambiar Estado
                                        </a>
                                        <a class="dropdown-item text-danger" href="asesores_delete.php?id=<?= $asesor['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este asesor?')">
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
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($pagina_actual > 1): ?>
                        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual - 1])) ?>">&laquo;</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= ($i == $pagina_actual) ? 'active' : ''; ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($pagina_actual < $total_paginas): ?>
                        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual + 1])) ?>">&raquo;</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
