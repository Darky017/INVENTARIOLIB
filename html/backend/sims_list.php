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
    $where = "WHERE ecs.numero LIKE :search 
              OR ecs.n_serie LIKE :search 
              OR u.primer_nombre LIKE :search 
              OR u.apellido_paterno LIKE :search 
              OR ec.marca LIKE :search 
              OR ec.modelo LIKE :search";
    $params[':search'] = "%$search%";
}

// Total de registros
$total_sql = "SELECT COUNT(*) 
              FROM equipo_cel_sim ecs 
              LEFT JOIN equipo_celular ec ON ecs.id_celular = ec.id 
              LEFT JOIN usuarios u ON ec.id_usuario = u.id 
              $where";

$total_stmt = $pdo->prepare($total_sql);
foreach ($params as $key => $value) {
    $total_stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$total_stmt->execute();
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta principal
$sql = "SELECT 
            ecs.*, 
            ec.n_serie AS celular_serie, ec.marca, ec.modelo, ec.id AS celular_id,
            u.primer_nombre, u.apellido_paterno
        FROM equipo_cel_sim ecs
        LEFT JOIN equipo_celular ec ON ecs.id_celular = ec.id
        LEFT JOIN usuarios u ON ec.id_usuario = u.id
        $where
        ORDER BY ecs.id DESC
        LIMIT :inicio, :registros";

$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registros', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$sims = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- 🟢 Interfaz HTML -->
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 d-flex justify-content-between align-items-center mb-3">
            <h4>Lista de SIMs</h4>
            <a href="sim_add_independiente.php" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-2"></i>Agregar SIM
            </a>
        </div>

        <!-- Barra de búsqueda -->
        <div class="col-md-6 mb-3">
            <form method="GET" action="">
                <input type="text" name="search" class="form-control" placeholder="Buscar número, serie, usuario..." value="<?php echo htmlspecialchars($search); ?>" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }">
            </form>
        </div>

        <!-- Tabla de resultados -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>Numero</th>
                            <th>N. Serie</th>
                            <th>Estado</th>
                            <th>Compañía</th>
                            <th>Versión</th>
                            <th>¿Plan?</th>
                            <th>Usuario Asignado</th>
                            <th>Celular Asignado</th>
                            <th>Fecha Asignación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
    <?php foreach ($sims as $sim): ?>
    <tr>
        <td><?php echo htmlspecialchars($sim['numero']); ?></td>
        <td><?php echo htmlspecialchars($sim['n_serie']); ?></td>
        <td>
    <?php 
        if (!empty($sim['id_celular'])) {
            echo 'Asignado';
        } else {
            echo 'No Asignado';
        }
    ?>
</td>

        <td><?php echo htmlspecialchars($sim['compania']); ?></td>
        <td><?php echo htmlspecialchars($sim['version']); ?></td>
       <td><?php echo $sim['es_plan'] === 'Sí' ? 'Sí' : 'No'; ?></td>

        <td>
            <?php 
                if ($sim['primer_nombre']) {
                    echo htmlspecialchars($sim['primer_nombre'] . ' ' . $sim['apellido_paterno']);
                } else {
                    echo 'Sin Usuario';
                }
            ?>
        </td>
        <td>
            <?php 
                if ($sim['marca']) {
                    echo htmlspecialchars($sim['marca'] . ' ' . $sim['modelo']);
                } else {
                    echo 'Sin Asignar';
                }
            ?>
        </td>
        <td>
    <?php
    if (!empty($sim['fecha_asignacion']) && $sim['fecha_asignacion'] !== '0000-00-00') {
        $fecha = strtotime($sim['fecha_asignacion']);
        $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        echo date('d', $fecha) . ' ' . $meses[date('n', $fecha) - 1] . ' ' . date('Y', $fecha);
    } else {
        echo 'No Asignado';
    }
    ?>
</td>

        <td>
    <div class="dropdown">
        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenu<?= $sim['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Acciones
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu<?= $sim['id'] ?>">
           <a class="dropdown-item" href="sim_edit_global.php?id=<?= $sim['id'] ?>">
    <i class="fas fa-edit mr-2 text-warning"></i>Editar
</a>

            <a class="dropdown-item text-danger" href="sim_delete_direct.php?id=<?= $sim['id'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta SIM?');">
                <i class="fas fa-trash-alt mr-2"></i>Eliminar
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-success" href="sim_view.php?id=<?= $sim['id'] ?>#qr">
                <i class="fas fa-qrcode"></i> Generar QR
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
