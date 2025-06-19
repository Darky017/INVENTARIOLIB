<?php
// sistema_view.php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM sistema WHERE equipo_id = :id");
$stmt->execute(['id' => $id]);
$sistema = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php if ($sistema): ?>
    <p><strong>Versión SO:</strong> <?= htmlspecialchars($sistema['version_so']) ?></p>
    <p><strong>¿Táctil?:</strong> <?= htmlspecialchars($sistema['tactil']) ?></p>
    <p><strong>IP Fija:</strong> <?= htmlspecialchars($sistema['ip_fija']) ?></p>
    <p><strong>MAC WiFi:</strong> <?= htmlspecialchars($sistema['mac_wifi']) ?></p>
    <p><strong>MAC Ethernet:</strong> <?= htmlspecialchars($sistema['mac_ethernet']) ?></p>
    <p><strong>Nombre en Red:</strong> <?= htmlspecialchars($sistema['nombre_equipo_red']) ?></p>
    
    <!-- Botón para editar el sistema -->
    <a href="sistema_edit.php?id=<?= $id ?>" class="btn btn-warning btn-sm">Editar Sistema</a>

<?php else: ?>
    <p>No hay información del sistema.</p>
    <a href="sistema_add.php?id=<?= $id ?>" class="btn btn-primary">Registrar Sistema</a>
<?php endif; ?>
