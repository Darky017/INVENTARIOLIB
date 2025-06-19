<?php
// bitlocker_view.php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';
require_once 'header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: equipos_list.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM bitlocker WHERE equipo_id = :id");
$stmt->execute(['id' => $id]);
$bitlocker = $stmt->fetch(PDO::FETCH_ASSOC);
?>


    <div class="card">
      
        <div class="card-body">
            <?php if ($bitlocker): ?>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($bitlocker['estado']); ?></p>
                <p><strong>ID. de Objeto de Dispositivo</strong> <?php echo htmlspecialchars($bitlocker['ID_Objeto_Dispositivo']); ?></p>
                <p><strong>Clave de Recuperación:</strong> <?php echo htmlspecialchars($bitlocker['clave_recuperacion']); ?></p>
                <a href="bitlocker_add.php?id=<?php echo $id; ?>" class="btn btn-warning">Editar</a>
            <?php else: ?>
                <p class="text-muted">Sin información de BitLocker.</p>
                <a href="bitlocker_add.php?id=<?php echo $id; ?>" class="btn btn-primary">Registrar</a>
            <?php endif; ?>
        </div>
    </div>
