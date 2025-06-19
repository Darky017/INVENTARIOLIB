<?php
session_start();
require_once '../config.php';
require_once 'header.php';

// Paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = '';
$params = [];

if ($search) {
    $where = "WHERE u.primer_nombre LIKE :search 
              OR u.apellido_paterno LIKE :search 
              OR r.tipo_equipo LIKE :search 
              OR r.estado LIKE :search";
    $params[':search'] = "%$search%";
}

// Total de registros
$total_sql = "SELECT COUNT(*) 
              FROM responsivas r
              LEFT JOIN usuarios u ON r.id_usuario = u.id
              $where";
$total_stmt = $pdo->prepare($total_sql);
foreach ($params as $key => $value) {
    $total_stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$total_stmt->execute();
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta principal
$sql = "SELECT r.*, u.primer_nombre, u.apellido_paterno 
        FROM responsivas r
        LEFT JOIN usuarios u ON r.id_usuario = u.id
        $where
        ORDER BY r.fecha_subida DESC
        LIMIT :inicio, :registros";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registros', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$responsivas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 🟢 Interfaz HTML -->
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 d-flex justify-content-between align-items-center mb-3">
            <h4>Lista de Responsivas</h4>
            <a href="responsiva_add.php" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-2"></i>Subir Responsiva
            </a>
        </div>

        <!-- Barra de búsqueda -->
        <div class="col-md-6 mb-3">
            <form method="GET" action="">
                <input type="text" name="search" class="form-control" placeholder="Buscar por usuario, equipo o estado..." value="<?php echo htmlspecialchars($search); ?>" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }">
            </form>
        </div>

        <!-- Tabla de resultados -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>Usuario</th>
                            <th>Equipo</th>
                            <th>Archivo</th>
                            <th>Estado</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        <?php foreach ($responsivas as $r): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['primer_nombre'] . ' ' . $r['apellido_paterno']); ?></td>
                            <td><?php echo htmlspecialchars($r['tipo_equipo']); ?></td>
                            <td>
                                <a href="../uploads/responsivas/<?php echo urlencode($r['ruta_archivo']); ?>" target="_blank">
                                    <i class="fas fa-file-pdf text-danger mr-1"></i><?php echo htmlspecialchars($r['nombre_archivo']); ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $r['estado'] === 'Vigente' ? 'success' : 'secondary'; ?>">
                                    <?php echo $r['estado']; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($r['descripcion']); ?></td>
                                <td> <?php
    if (!empty($r['fecha_subida']) && $r['fecha_subida'] !== '0000-00-00') {
        $fecha = strtotime($r['fecha_subida']);
        $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        echo date('d', $fecha) . ' ' . $meses[date('n', $fecha) - 1] . ' ' . date('Y', $fecha);
    } else {
        echo 'No Asignado';
    }
    ?></td>
                            <td>
    <div class="dropdown">
        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenu<?= $r['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Acciones
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu<?= $r['id'] ?>">
            <a class="dropdown-item" href="responsiva_download.php?id=<?= $r['id'] ?>">
                <i class="fas fa-download mr-2 text-info"></i>Descargar
            </a>
            <a class="dropdown-item" href="responsiva_edit.php?id=<?= $r['id'] ?>">
                <i class="fas fa-edit mr-2 text-warning"></i>Editar
            </a>
            <a class="dropdown-item text-danger" href="responsiva_delete.php?id=<?= $r['id'] ?>">
                <i class="fas fa-trash-alt mr-2"></i>Eliminar
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
                        <li class="page-item"><a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>&search=<?php echo urlencode($search); ?>">&laquo;</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($pagina_actual < $total_paginas): ?>
                        <li class="page-item"><a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>&search=<?php echo urlencode($search); ?>">&raquo;</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
