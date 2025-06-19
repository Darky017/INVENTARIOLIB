<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: celulares_list.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM esim WHERE id_celular = :id");
$stmt->execute(['id' => $id]);
$esim = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php if ($esim): ?>
    <p><strong>Número:</strong> <?= htmlspecialchars($esim['numero']) ?></p>
    <p><strong>Número de Serie:</strong> <?= htmlspecialchars($esim['n_serie']) ?></p>
    <p><strong>Compañía:</strong> <?= htmlspecialchars($esim['compania']) ?></p>
    <p><strong>Plan:</strong> <?= htmlspecialchars($esim['es_plan'] ? 'Sí' : 'No') ?></p>
    <p><strong>Fecha de Asignación:</strong> <?= htmlspecialchars($esim['fecha_asignacion']) ?></p>
    <a href="esim_edit.php?id=<?= $esim['id'] ?>&equipo_id=<?= $id ?>" class="btn btn-warning btn-sm">Editar eSIM</a>
<a href="esim_delete.php?id=<?= $celular['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar eSIM de este celular?')">
    <i class="fas fa-trash-alt"></i> Eliminar eSIM
</a>

<?php else: ?>
    <p>No hay información de la eSIM.</p>
    <a href="esim_add.php?id=<?= $id ?>">Registrar eSIM</a>
<?php endif; ?>
