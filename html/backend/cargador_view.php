<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cargador WHERE equipo_id = :id");
$stmt->execute(['id' => $id]);
$cargador = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php if ($cargador): ?>
    <p><strong>Marca:</strong> <?= htmlspecialchars($cargador['marca']) ?></p>
    <p><strong>Número de Serie:</strong> <?= htmlspecialchars($cargador['numero_serie']) ?></p>
    <p><strong>Modelo:</strong> <?= htmlspecialchars($cargador['modelo']) ?></p>
    <p><strong>Watts:</strong> <?= htmlspecialchars($cargador['watts']) ?></p>
    
    <!-- Asegúrate de que el parámetro 'id' y 'equipo_id' sean correctos -->
    <a href="cargador_edit.php?id=<?= $cargador['id'] ?>&equipo_id=<?= $id ?>" class="btn btn-warning btn-sm">Editar Cargador</a>
    
    
<?php else: ?>
    <p>No hay información del cargador.</p>
    <a href="cargador_add.php?id=<?= $id ?>">Registrar Cargador</a>
<?php endif; ?>

