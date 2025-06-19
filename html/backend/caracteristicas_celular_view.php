<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: celulares_list.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM caracteristicas_celular WHERE id_celular = :id");
$stmt->execute(['id' => $id]);
$caracteristicas = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php if ($caracteristicas): ?>
    <p><strong>RAM:</strong> <?= htmlspecialchars($caracteristicas['ram']) ?> GB</p>
    <p><strong>Almacenamiento:</strong> <?= htmlspecialchars($caracteristicas['almacenamiento']) ?> GB</p>
    <p><strong>Procesador:</strong> <?= htmlspecialchars($caracteristicas['procesador']) ?></p>
    <a href="caracteristicas_celular_edit.php?id=<?= $celular['id'] ?>" class="btn btn-sm btn-warning">
    <i class="fas fa-edit"></i> Editar
</a>
    <a href="caracteristicas_celular_delete.php?id=<?= $celular['id'] ?>" class="btn btn-sm btn-danger">
    <i class="fas fa-trash"></i> Eliminar
</a>
<?php else: ?>
    <p>No hay información de las características técnicas.</p>
    <a href="caracteristicas_celular_add.php?id=<?= $id ?>">Registrar Características</a>
<?php endif; ?>
