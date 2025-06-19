<?php
ob_start();
session_start();
require_once '../config.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    die("ID no especificado.");
}

$id = (int) $_GET['id'];

// Obtener la responsiva actual
$stmt = $pdo->prepare("SELECT * FROM responsivas WHERE id = ?");
$stmt->execute([$id]);
$responsiva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$responsiva) {
    die("Responsiva no encontrada.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $tipo_equipo = $_POST['tipo_equipo'];

    if (!empty($_FILES['archivo']['name'])) {
        $nombre_original = $_FILES['archivo']['name'];
        $nombre_archivo = uniqid() . '_' . $nombre_original;
        $ruta_archivo = 'uploads/' . $nombre_archivo;
        move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_archivo);
    } else {
        $nombre_archivo = $responsiva['nombre_archivo'];
        $ruta_archivo = $responsiva['ruta_archivo'];
    }

    $sql = "UPDATE responsivas SET 
                id_usuario = :id_usuario,
                descripcion = :descripcion,
                estado = :estado,
                tipo_equipo = :tipo_equipo,
                nombre_archivo = :nombre_archivo,
                ruta_archivo = :ruta_archivo
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':descripcion' => $descripcion,
        ':estado' => $estado,
        ':tipo_equipo' => $tipo_equipo,
        ':nombre_archivo' => $nombre_archivo,
        ':ruta_archivo' => $ruta_archivo,
        ':id' => $id
    ]);

    header("Location: responsivas_list.php");
    exit;
}
?>

<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow rounded-lg w-100" style="max-width: 600px;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-edit mr-2"></i>Editar Responsiva</h5>
            <a href="responsivas_list.php" class="btn btn-sm btn-light">
                <i class="fas fa-arrow-left mr-1"></i>Volver
            </a>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><strong>Usuario asignado</strong></label>
                    <select name="id_usuario" class="form-control" required>
                        <option value="">-- Seleccionar --</option>
                        <?php
                        $usuarios = $pdo->query("SELECT id, primer_nombre, apellido_paterno FROM usuarios ORDER BY primer_nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($usuarios as $u):
                            $selected = ($u['id'] == $responsiva['id_usuario']) ? 'selected' : '';
                        ?>
                            <option value="<?= $u['id'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($u['primer_nombre'] . ' ' . $u['apellido_paterno']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><strong>Tipo de equipo</strong></label>
                    <input type="text" name="tipo_equipo" class="form-control" placeholder="Ej: Laptop, Mouse, Celular" value="<?= htmlspecialchars($responsiva['tipo_equipo']) ?>">
                </div>

                <div class="form-group">
                    <label><strong>Descripción</strong></label>
                    <textarea name="descripcion" rows="2" class="form-control" placeholder="Detalles del equipo"><?= htmlspecialchars($responsiva['descripcion']) ?></textarea>
                </div>

                <div class="form-group">
                    <label><strong>Estado</strong></label>
                    <select name="estado" class="form-control" required>
                        <option value="Vigente" <?= $responsiva['estado'] == 'Vigente' ? 'selected' : '' ?>>Vigente</option>
                        <option value="Baja" <?= $responsiva['estado'] == 'Baja' ? 'selected' : '' ?>>Baja</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><strong>Archivo PDF</strong></label>
                    <input type="file" name="archivo" class="form-control-file" accept="application/pdf">
                    <small class="form-text text-muted mt-1">
                        Dejar vacío para mantener: <strong><?= htmlspecialchars($responsiva['nombre_archivo']) ?></strong>
                    </small>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success mr-2">
                        <i class="fas fa-save mr-1"></i>Actualizar
                    </button>
                    <a href="responsivas_list.php" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
<?php ob_end_flush(); ?>
