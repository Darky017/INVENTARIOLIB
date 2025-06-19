<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

// Configurar idioma a español
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp', 'spanish');

if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM garantia WHERE equipo_id = :id");
$stmt->execute(['id' => $id]);
$garantia = $stmt->fetch(PDO::FETCH_ASSOC);

// Función para formatear fecha con mes en español
function formatearFecha($fecha) {
    if (!$fecha || $fecha == "0000-00-00") {
        return "Sin fecha";
    }
    return strftime("%d de %B de %Y", strtotime($fecha)); 
}

// Función para determinar si está activa
function garantiaActiva($garantia) {
    if (!$garantia) return false;
    if ($garantia['estado'] !== 'Activado') return false;
    if (!$garantia['fecha_garantia']) return false;
    return strtotime($garantia['fecha_garantia']) >= strtotime(date('Y-m-d'));
}

$activa = garantiaActiva($garantia);
?>

<?php if ($garantia): ?>
    <?php if ($activa): ?>
        <p><strong>Estado:</strong> <span class="badge bg-success">Garantía Activa</span></p>
        <p><strong>Fecha de Inicio:</strong> <?= htmlspecialchars(formatearFecha($garantia['fecha_registro'])) ?></p>
        <p><strong>Fecha de Finalización:</strong> <?= htmlspecialchars(formatearFecha($garantia['fecha_garantia'])) ?></p>
        <p><strong>Comentarios:</strong> <?= htmlspecialchars($garantia['notas']) ?></p>
    <?php else: ?>
        <p><strong>Estado:</strong> <span class="badge bg-danger">Sin garantía activa</span></p>
        <?php if ($garantia['fecha_registro'] || $garantia['fecha_garantia']): ?>
            <p><strong>Fecha de Inicio:</strong> <?= htmlspecialchars(formatearFecha($garantia['fecha_registro'])) ?></p>
            <p><strong>Fecha de Finalización:</strong> <?= htmlspecialchars(formatearFecha($garantia['fecha_garantia'])) ?></p>
            <p><strong>Comentarios:</strong> <?= htmlspecialchars($garantia['notas']) ?></p>
        <?php else: ?>
            <p><strong>Comentarios:</strong> <?= htmlspecialchars($garantia['notas']) ?></p>
            <p>Este equipo no cuenta con garantía activa.</p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Botón para editar la garantía -->
    <a href="garantia_edit.php?id=<?= $id ?>" class="btn btn-warning btn-sm">Editar Garantía</a>

<?php else: ?>
    <p>No hay información de garantía.</p>
    <a href="garantia_add.php?id=<?= $id ?>" class="btn btn-primary btn-sm">Registrar Garantía</a>
<?php endif; ?>
