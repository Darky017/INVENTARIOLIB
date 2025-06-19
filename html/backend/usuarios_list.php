<?php
session_start();
require_once '../config.php';
require_once 'header.php';

// Paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Obtener total de registros
$where = '';
if ($search) {
    $where = "WHERE usuarios.primer_nombre LIKE :search 
              OR usuarios.apellido_paterno LIKE :search 
              OR usuarios.correo_personal LIKE :search 
              OR equipo_computo.serial_pc LIKE :search 
              OR equipo_celular.n_serie LIKE :search";
}

$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios 
                            LEFT JOIN equipo_computo ON usuarios.id = equipo_computo.usuario 
                            LEFT JOIN equipo_celular ON usuarios.id = equipo_celular.id_usuario
                            $where");
if ($search) {
    $total_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$total_stmt->execute();
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener usuarios con su equipo asignado
$stmt = $pdo->prepare("SELECT usuarios.*, equipo_computo.id AS pc_id, equipo_computo.serial_pc, equipo_celular.id AS celular_id, equipo_celular.n_serie
    FROM usuarios
    LEFT JOIN equipo_computo ON usuarios.id = equipo_computo.usuario
    LEFT JOIN equipo_celular ON usuarios.id = equipo_celular.id_usuario
    $where
    ORDER BY usuarios.id DESC
    LIMIT :inicio, :registros");

$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registros', $registros_por_pagina, PDO::PARAM_INT);

if ($search) {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 🟢 Interfaz HTML -->
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <h4 class="mb-3">Lista de Usuarios</h4>
                <a href="usuarios_add.php" class="btn btn-primary add-list">
                    <i class="fas fa-user-plus mr-2"></i>Agregar Usuario
                </a>
            </div>
        </div>

        <!-- 🟢 Barra de búsqueda -->
        <div class="col-md-6 mb-3">
            <form method="GET" action="">
                <input type="text" name="search" class="form-control" placeholder="Buscar usuario, número de serie de PC o celular..." value="<?php echo htmlspecialchars($search); ?>" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }">
            </form>
        </div>

        <!-- 🟢 Tabla de usuarios -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>Primer Nombre</th>
                            <th>Segundo Nombre</th>
                            <th>Primer Apellido</th>
                            <th>Segundo Apellido</th>
                            <th>Correo Corporativo</th>
                            <th>Número de Serie PC</th>
                            <th>Número de Serie Celular</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['primer_nombre']); ?></td>
                            <td><?php 
                if ($usuario['segundo_nombre']) {
                    echo htmlspecialchars($usuario['segundo_nombre']);
                } else {
                    echo 'N/A';
                }
            ?></td>
                            <td><?php echo htmlspecialchars($usuario['apellido_paterno']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['apellido_materno']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['correo_corporativo']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['serial_pc'] ?? 'Sin equipo'); ?></td>
                            <td><?php echo htmlspecialchars($usuario['n_serie'] ?? 'Sin celular'); ?></td>
                            <td class="text-center">
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="accionesUsuario<?= $usuario['id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Acciones
        </button>
        <div class="dropdown-menu" aria-labelledby="accionesUsuario<?= $usuario['id']; ?>">
            <?php if ($usuario['pc_id']): ?>
                <a class="dropdown-item text-info" href="equipos_detalles.php?id=<?= $usuario['pc_id']; ?>">
                    <i class="fas fa-laptop"></i> Ver PC
                </a>
            <?php endif; ?>
            <?php if ($usuario['celular_id']): ?>
                <a class="dropdown-item text-info" href="celulares_detalles.php?id=<?= $usuario['celular_id']; ?>">
                    <i class="fas fa-mobile-alt"></i> Ver Celular
                </a>
            <?php endif; ?>
            <a class="dropdown-item text-success" href="usuarios_edit.php?id=<?= $usuario['id']; ?>">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a class="dropdown-item text-danger" href="usuarios_delete.php?id=<?= $usuario['id']; ?>">
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

        <!-- 🟢 Paginación -->
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
