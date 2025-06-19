<?php
require_once '../config.php';
require_once 'header.php';

// Obtener ID desde GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "<script>alert('ID inválido'); window.location.href='celulares_list.php';</script>";
    exit;
}

// Obtener lista de usuarios
$stmt_usuarios = $pdo->query("SELECT id, primer_nombre, segundo_nombre, apellido_paterno, apellido_materno FROM usuarios ORDER BY primer_nombre");
$usuarios = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);

// Obtener datos del celular
$stmt = $pdo->prepare("SELECT * FROM equipo_celular WHERE id = :id");
$stmt->execute([':id' => $id]);
$celular = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$celular) {
    echo "<script>alert('Celular no encontrado'); window.location.href='celulares_list.php';</script>";
    exit;
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = !empty($_POST["id_usuario"]) ? $_POST["id_usuario"] : null;
    $fecha_asignacion = !empty($_POST["fecha_asignacion"]) ? $_POST["fecha_asignacion"] : null;
    $marca = $_POST["marca"];
    $modelo = $_POST["modelo"];
    $imei1 = $_POST["imei1"];
    $imei2 = !empty($_POST["imei2"]) ? $_POST["imei2"] : 'No aplica';
    $n_serie = $_POST["n_serie"];
    $estado = $_POST["estado"];

    $update = $pdo->prepare("UPDATE equipo_celular SET 
        id_usuario = :id_usuario,
        fecha_asignacion = :fecha_asignacion,
        marca = :marca,
        modelo = :modelo,
        IMEI_1 = :imei1,
        IMEI_2 = :imei2,
        n_serie = :n_serie,
        estado = :estado
        WHERE id = :id");

    $update->execute([
        ':id_usuario' => $id_usuario,
        ':fecha_asignacion' => $fecha_asignacion,
        ':marca' => $marca,
        ':modelo' => $modelo,
        ':imei1' => $imei1,
        ':imei2' => $imei2,
        ':n_serie' => $n_serie,
        ':estado' => $estado,
        ':id' => $id
    ]);

    echo "<script>alert('Celular actualizado correctamente'); window.location.href = 'celulares_list.php';</script>";
    exit;
}
?>

<style>
    .container { max-width: 750px; margin-top: 30px; }
    .card { border-radius: 12px; }
    .form-group { margin-bottom: 12px; }
    .btn { padding: 6px 20px; }
</style>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark text-center">
            <h5 class="mb-0">Editar Celular Corporativo</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Usuario Asignado</strong></label>
                        <select class="form-control" name="id_usuario" id="id_usuario">
                            <option value="">Sin asignar</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id']; ?>" <?= $usuario['id'] == $celular['id_usuario'] ? 'selected' : ''; ?>>
                                    <?= $usuario['primer_nombre'] . ' ' . $usuario['segundo_nombre'] . ' ' . $usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group" id="fecha_asignacion_group">
                        <label><strong>Fecha de Asignación</strong></label>
                        <input type="date" class="form-control" name="fecha_asignacion" value="<?= $celular['fecha_asignacion']; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Marca</strong></label>
                        <input type="text" class="form-control" name="marca" value="<?= $celular['marca']; ?>" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>Modelo</strong></label>
                        <input type="text" class="form-control" name="modelo" value="<?= $celular['modelo']; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Número de Serie</strong></label>
                        <input type="text" class="form-control" name="n_serie" value="<?= $celular['n_serie']; ?>" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>Estado</strong></label>
                        <select class="form-control" name="estado" required>
                            <option value="Asignado" <?= $celular['estado'] === 'Asignado' ? 'selected' : ''; ?>>Asignado</option>
                            <option value="No asignado" <?= $celular['estado'] === 'No asignado' ? 'selected' : ''; ?>>No asignado</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>IMEI 1</strong></label>
                        <input type="text" class="form-control" name="imei1" value="<?= $celular['IMEI_1']; ?>" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>IMEI 2 (opcional)</strong></label>
                        <input type="text" class="form-control" name="imei2" value="<?= $celular['IMEI_2']; ?>">
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-warning text-dark"><i class="fas fa-save"></i> Guardar Cambios</button>
                    <a href="celulares_list.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const idUsuarioSelect = document.getElementById('id_usuario');
    const fechaGroup = document.getElementById('fecha_asignacion_group');

    function toggleFechaAsignacion() {
        if (idUsuarioSelect.value === "") {
            fechaGroup.style.display = "none";
        } else {
            fechaGroup.style.display = "block";
        }
    }

    idUsuarioSelect.addEventListener('change', toggleFechaAsignacion);
    window.addEventListener('DOMContentLoaded', toggleFechaAsignacion);
</script>

<?php require_once 'footer.php'; ?>
