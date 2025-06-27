<?php
session_start();
require_once '../config.php';
require_once 'header.php';

// Verificar si se proporciona un ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de impresora no válido";
    header("Location: impresoras_list.php");
    exit;
}

$impresora_id = (int) $_GET['id'];

// Obtener datos completos de la impresora con información del usuario
$stmt = $pdo->prepare("
    SELECT impresoras.*, 
           CONCAT(usuarios.primer_nombre, ' ', usuarios.apellido_paterno, ' ', usuarios.apellido_materno) AS usuario_completo,
           usuarios.correo_corporativo,
           usuarios.correo_personal
    FROM impresoras
    LEFT JOIN usuarios ON impresoras.usuario_id = usuarios.id
    WHERE impresoras.id = ?
");
$stmt->execute([$impresora_id]);
$impresora = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$impresora) {
    $_SESSION['error'] = "Impresora no encontrada";
    header("Location: impresoras_list.php");
    exit;
}

// Función para formatear fechas
function formatearFecha($fecha) {
    if (!$fecha || $fecha == "0000-00-00") {
        return "No aplica";
    }
    $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    $fecha_obj = new DateTime($fecha);
    return $fecha_obj->format('d') . ' ' . $meses[$fecha_obj->format('n') - 1] . ' ' . $fecha_obj->format('Y');
}

// Función para obtener el color del badge según el estado
function getEstadoColor($estado) {
    switch ($estado) {
        case 'En uso':
            return 'badge-success';
        case 'En Stock':
            return 'badge-info';
        case 'En mantenimiento':
            return 'badge-warning';
        case 'Fuera de servicio':
            return 'badge-secondary';
        default:
            return 'badge-secondary';
    }
}
?>

<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div><h4 class="mb-3">Detalles de la Impresora</h4></div>
                <div>
                    <a href="impresoras_edit.php?id=<?= $impresora_id ?>" class="btn btn-success">
                        <i class="las la-edit mr-2"></i> Editar
                    </a>
                    <a href="impresoras_list.php" class="btn btn-secondary">
                        <i class="las la-arrow-left mr-2"></i> Volver a la lista
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="las la-print mr-2"></i>
                        <?= htmlspecialchars($impresora['marca'] . ' ' . $impresora['modelo']); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información básica -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Información Básica</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Marca:</strong></td>
                                    <td><?= htmlspecialchars($impresora['marca']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Modelo:</strong></td>
                                    <td><?= htmlspecialchars($impresora['modelo']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Número de Serie:</strong></td>
                                    <td><code><?= htmlspecialchars($impresora['numero_serie']); ?></code></td>
                                </tr>
                                <tr>
                                    <td><strong>Contraseña:</strong></td>
                                    <td><?= !empty($impresora['contrasena']) ? htmlspecialchars($impresora['contrasena']) : 'No configurada'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td>
                                        <span class="badge <?= getEstadoColor($impresora['estado']); ?>">
                                            <?= htmlspecialchars($impresora['estado']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Departamento:</strong></td>
                                    <td><?= !empty($impresora['departamento']) ? htmlspecialchars($impresora['departamento']) : 'No asignado'; ?></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Información de asignación -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Información de Asignación</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Usuario Asignado:</strong></td>
                                    <td>
                                        <?php if (!empty($impresora['usuario_completo'])): ?>
                                            <strong><?= htmlspecialchars($impresora['usuario_completo']); ?></strong>
                                        <?php else: ?>
                                            <span class="text-muted">Sin asignar</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if (!empty($impresora['usuario_completo'])): ?>
                                <tr>
                                    <td><strong>Correo Corporativo:</strong></td>
                                    <td><?= htmlspecialchars($impresora['correo_corporativo']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Correo Personal:</strong></td>
                                    <td><?= htmlspecialchars($impresora['correo_personal']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><strong>Fecha de Asignación:</strong></td>
                                    <td><?= formatearFecha($impresora['fecha_asignacion']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de Creación:</strong></td>
                                    <td><?= formatearFecha($impresora['fecha_creacion']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Última Actualización:</strong></td>
                                    <td><?= formatearFecha($impresora['fecha_actualizacion']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <?php if (!empty($impresora['observaciones'])): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">Observaciones</h6>
                            <div class="alert alert-info">
                                <?= nl2br(htmlspecialchars($impresora['observaciones'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Acciones -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="impresoras_edit.php?id=<?= $impresora_id ?>" class="btn btn-success">
                                        <i class="las la-edit mr-2"></i> Editar Impresora
                                    </a>
                                    <a href="impresoras_list.php" class="btn btn-secondary">
                                        <i class="las la-list mr-2"></i> Ver Todas las Impresoras
                                    </a>
                                </div>
                                <div>
                                    <a href="impresoras_delete.php?id=<?= $impresora_id ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar esta impresora? Esta acción no se puede deshacer.')">
                                        <i class="las la-trash mr-2"></i> Eliminar Impresora
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?> 