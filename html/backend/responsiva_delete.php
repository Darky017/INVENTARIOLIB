<?php
ob_start();
session_start();
require_once '../config.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    die("ID no especificado.");
}

$id = (int) $_GET['id'];

// Obtener la responsiva
$stmt = $pdo->prepare("SELECT r.*, u.primer_nombre, u.apellido_paterno FROM responsivas r JOIN usuarios u ON r.id_usuario = u.id WHERE r.id = ?");
$stmt->execute([$id]);
$responsiva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$responsiva) {
    die("Responsiva no encontrada.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Eliminar archivo del servidor
    $archivo = '../uploads/responsivas/' . $responsiva['ruta_archivo'];
    if (file_exists($archivo)) {
        unlink($archivo);
    }

    // Eliminar registro de la base de datos
    $stmt = $pdo->prepare("DELETE FROM responsivas WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: responsivas_list.php");
    exit;
}
?>

<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow rounded-lg w-100" style="max-width: 500px;">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-trash-alt mr-2"></i>Eliminar Responsiva</h5>
        </div>
        <div class="card-body">
            <p class="text-center">
                ¿Estás seguro que deseas eliminar la responsiva del usuario<br>
                <strong><?= htmlspecialchars($responsiva['primer_nombre'] . ' ' . $responsiva['apellido_paterno']) ?></strong> 
                sobre el equipo <strong><?= htmlspecialchars($responsiva['tipo_equipo']) ?></strong>?
            </p>

            <div class="text-center mb-3">
                <p class="mb-1 text-muted">Archivo actual:</p>
                <a href="../uploads/responsivas/<?= urlencode($responsiva['ruta_archivo']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-file-pdf mr-1"></i><?= htmlspecialchars($responsiva['nombre_archivo']) ?>
                </a>
            </div>

            <form method="POST" class="d-flex justify-content-between">
                <a href="responsivas_list.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash mr-1"></i>Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
<?php ob_end_flush(); ?>
