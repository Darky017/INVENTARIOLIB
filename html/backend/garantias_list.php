<?php
session_start();
require_once '../config.php';
require_once 'header.php'; // Incluir encabezado con sesión activa

// Definir cuántos registros se mostrarán por página
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener total de registros
$total_stmt = $pdo->query("SELECT COUNT(*) FROM garantia");
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener registros paginados con el equipo asociado
$stmt = $pdo->prepare("
    SELECT garantia.*, equipo_computo.nombre_pc, equipo_computo.marca_pc, equipo_computo.serial_pc
    FROM garantia 
    LEFT JOIN equipo_computo ON garantia.equipo_id = equipo_computo.id
    ORDER BY garantia.id DESC
    LIMIT :inicio, :registros
");
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registros', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$garantias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Lista de Garantías</h4>
                    <p class="mb-0">Administra las garantías registradas.</p>
                </div>
                <a href="garantias_add.php" class="btn btn-primary add-list">
                    <i class="las la-plus mr-2"></i>Agregar Garantía
                </a>
            </div>
        </div>

        <!-- Tabla de garantías -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>ID</th>
                            <th>Equipo</th>
                            <th>Marca</th>
                            <th>Serial</th>
                            <th>Fecha Registro</th>
                            <th>Fecha Garantía</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        <?php foreach ($garantias as $garantia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($garantia['id']); ?></td>
                            <td><?php echo htmlspecialchars($garantia['nombre_pc']); ?></td>
                            <td><?php echo htmlspecialchars($garantia['marca_pc']); ?></td>
                            <td><?php echo htmlspecialchars($garantia['serial_pc']); ?></td>
                            <td><?php echo htmlspecialchars($garantia['fecha_registro']); ?></td>
                            <td><?php echo htmlspecialchars($garantia['fecha_garantia']); ?></td>
                            <td>
                                <a href="garantia_view.php?id=<?php echo $garantia['id']; ?>" class="btn btn-info btn-sm">
                                    <i class="ri-eye-line"></i> Ver
                                </a>
                                <a href="garantia_edit.php?id=<?php echo $garantia['id']; ?>" class="btn btn-success btn-sm">
                                    <i class="ri-pencil-line"></i> Editar
                                </a>
                                <a href="garantia_delete.php?id=<?php echo $garantia['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta garantía?')">
                                    <i class="ri-delete-bin-line"></i> Eliminar
                                </a>
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
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pagina_actual < $total_paginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
