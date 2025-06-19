<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: celulares_list.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cargador_celular WHERE id_celular = :id");
$stmt->execute(['id' => $id]);
$cargador = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php if ($cargador): ?>
    <p><strong>Marca:</strong> <?= htmlspecialchars($cargador['marca']) ?></p>
    <p><strong>Número de Serie:</strong> <?= htmlspecialchars($cargador['n_serie']) ?></p>
    <p><strong>Modelo:</strong> <?= htmlspecialchars($cargador['modelo']) ?></p>
    <p><strong>Watts:</strong> <?= htmlspecialchars($cargador['watts']) ?></p>
    <div>
    <a href="cargador_celular_edit.php?id=<?= $id ?>" class="btn btn-warning btn-sm mt-2">Editar Cargador</a>
    <a href="cargador_celular_delete.php?id=<?= $celular['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar cargador de este celular?')">
    <i class="fas fa-trash-alt"></i> Eliminar Cargador
</a>
</div>
<?php else: ?>
    

    <p>No hay información del cargador.</p>
    <a href="cargador_celular_edit.php?id=<?= $id ?>" class="btn btn-primary btn-sm">Registrar Cargador</a>
<?php endif; ?>

