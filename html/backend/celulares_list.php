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

// Buscar en los parámetros de la URL
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

// Construir la parte de la consulta de búsqueda
$where = "";
if ($buscar) {
    $where = "WHERE (equipo_celular.marca LIKE :buscar OR 
                     equipo_celular.modelo LIKE :buscar OR 
                     equipo_celular.n_serie LIKE :buscar OR 
                     equipo_celular.IMEI_1 LIKE :buscar OR 
                     equipo_celular.IMEI_2 LIKE :buscar)";
}

// Consulta para obtener el total de registros
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM equipo_celular $where");
if ($buscar) {
    $total_stmt->bindValue(':buscar', "%$buscar%", PDO::PARAM_STR);
}
$total_stmt->execute();
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta para obtener los registros con paginación y filtro de búsqueda
$stmt = $pdo->prepare("
    SELECT equipo_celular.*, 
           CONCAT(usuarios.primer_nombre, ' ', usuarios.apellido_paterno) AS usuario_asignado
    FROM equipo_celular
    LEFT JOIN usuarios ON equipo_celular.id_usuario = usuarios.id
    $where
    ORDER BY equipo_celular.id DESC
    LIMIT :inicio, :registros
");

if ($buscar) {
    $stmt->bindValue(':buscar', "%$buscar%", PDO::PARAM_STR);
}

$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registros', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$celulares = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.dropdown-menu {
    max-height: 300px;   /* Puedes ajustar el alto */
    overflow-y: auto;
    min-width: 200px;    /* Opcional: hazlo más ancho si lo necesitas */
}
</style>

<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Lista de Celulares</h4>
                </div>
                <a href="celulares_add.php" class="btn btn-primary add-list">
                    <i class="las la-plus mr-2"></i> Agregar Celular
                </a>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <form method="GET" action="">
                <input type="text" name="buscar" id="searchInput" class="form-control" value="<?= htmlspecialchars($buscar); ?>" placeholder="Buscar por serial, marca, modelo...">
            </form>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table table-hover">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data text-center">
                            <th>Usuario Asignado</th>
                            <th>Fecha Asignación</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>IMEI 1</th>
                            <th>IMEI 2</th>
                            <th>Serie</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body text-center">
                        <?php foreach ($celulares as $celular): ?>
                        <tr>
                            <td><?= !empty($celular['usuario_asignado']) ? htmlspecialchars($celular['usuario_asignado']) : 'Sin asignar'; ?></td>
                            <td>
    <?php
    if (!empty($celular['fecha_asignacion']) && $celular['fecha_asignacion'] !== '0000-00-00') {
        $fecha = strtotime($celular['fecha_asignacion']);
        $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        echo date('d', $fecha) . ' ' . $meses[date('n', $fecha) - 1] . ' ' . date('Y', $fecha);
    } else {
        echo 'No Asignado';
    }
    ?>
</td>

                            
                            <td><?= htmlspecialchars($celular['marca']); ?></td>
                            <td><?= htmlspecialchars($celular['modelo']); ?></td>
                            <td><?= htmlspecialchars($celular['IMEI_1']); ?></td>
                            <td><?= htmlspecialchars($celular['IMEI_2']); ?></td>
                            <td><?= htmlspecialchars($celular['n_serie']); ?></td>
                            <td>
                                <span class="badge 
                                    <?= $celular['estado'] === 'Asignado' ? 'badge-success' : 'badge-secondary'; ?>">
                                    <?= htmlspecialchars($celular['estado']); ?>
                                </span>
                            </td>
                          <td class="text-center">
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="accionesCelular<?= $celular['id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Acciones
        </button>
        <div class="dropdown-menu" aria-labelledby="accionesCelular<?= $celular['id']; ?>">
            <a class="dropdown-item text-info" href="celulares_detalles.php?id=<?= $celular['id']; ?>">
                <i class="fas fa-eye"></i> Ver Detalles
            </a>
            <a class="dropdown-item text-success" href="celulares_edit.php?id=<?= $celular['id']; ?>">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a class="dropdown-item text-danger" href="celulares_delete.php?id=<?= $celular['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este celular?')">
                <i class="fas fa-trash-alt"></i> Eliminar
            </a>
            <div class="dropdown-divider"></div>

            <a class="dropdown-item text-success" href="celulares_detalles.php?id=<?= $celular['id']; ?>#qr">
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

            <!-- Paginación -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= $i == $pagina_actual ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?= $i; ?>&buscar=<?= urlencode($buscar); ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Bootstrap JS + jQuery + Popper para Bootstrap 4 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
