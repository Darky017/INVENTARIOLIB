<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM caracteristicas_tecnicas WHERE equipo_id = :id");
$stmt->execute(['id' => $id]);
$caracteristicas = $stmt->fetch(PDO::FETCH_ASSOC);
?>


            <?php if ($caracteristicas): ?>
                <p><strong>Procesador:</strong> <?php echo htmlspecialchars($caracteristicas['procesador']); ?></p>
                <p><strong>RAM:</strong> <?php echo htmlspecialchars($caracteristicas['ram']); ?></p>
                <p><strong>Tipo de Almacenamiento:</strong> <?php echo htmlspecialchars($caracteristicas['almacenamiento_tipo']); ?></p>
                <p><strong>Capacidad de Almacenamiento:</strong> <?php echo htmlspecialchars($caracteristicas['almacenamiento_capacidad']); ?></p>
                <p><strong>Tipo de Almacenamiento Secundario:</strong>
                    <?php
                        echo !empty($caracteristicas['almacenamiento_tipo2']) ? htmlspecialchars($caracteristicas['almacenamiento_tipo2']) : 'No Aplica';
                    ?>
                </p>
                <p><strong>Capacidad de Almacenamiento Secundario:</strong> <?php echo htmlspecialchars($caracteristicas['almacenamiento_capacidad2']); ?></p>
                <p><strong>Tarjeta de Video:</strong> <?php echo htmlspecialchars($caracteristicas['tarjeta_video']); ?></p>
                <p><strong>Estado de la Batería:</strong> <?php echo htmlspecialchars($caracteristicas['estado_bateria']); ?></p>
                <a href="caracteristicas_edit.php?id=<?= $id ?>" class="btn btn-warning btn-sm">
    <i class="fas fa-edit"></i> Editar
</a>

            <?php else: ?>
                <p class="text-muted text-center">Sin información de características técnicas.</p>
                <div class="text-center">
                    <a href="caracteristicas_add.php?id=<?php echo $id; ?>" class="btn btn-primary">Registrar Características</a>
                </div>
            <?php endif; ?>
        