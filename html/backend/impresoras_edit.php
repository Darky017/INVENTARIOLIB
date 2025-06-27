<?php
session_start();
require_once '../config.php';

// Verificar si se proporciona un ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de impresora no válido";
    header("Location: impresoras_list.php");
    exit;
}

$impresora_id = (int) $_GET['id'];

// Obtener datos de la impresora
$stmt = $pdo->prepare("SELECT * FROM impresoras WHERE id = ?");
$stmt->execute([$impresora_id]);
$impresora = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$impresora) {
    $_SESSION['error'] = "Impresora no encontrada";
    header("Location: impresoras_list.php");
    exit;
}

// Obtener lista de usuarios para el select
$usuarios = $pdo->query("SELECT id, primer_nombre, apellido_paterno, apellido_materno FROM usuarios ORDER BY primer_nombre")->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validar datos
        $marca = trim($_POST['marca']);
        $modelo = trim($_POST['modelo']);
        $numero_serie = trim($_POST['numero_serie']);
        $contrasena = trim($_POST['contrasena']);
        $usuario_id = !empty($_POST['usuario_id']) ? $_POST['usuario_id'] : null;
        $departamento = trim($_POST['departamento']);
        $estado = $_POST['estado'];
        $observaciones = trim($_POST['observaciones']);

        // Validaciones
        if (empty($marca)) {
            throw new Exception("La marca es obligatoria");
        }
        if (empty($modelo)) {
            throw new Exception("El modelo es obligatorio");
        }
        if (empty($numero_serie)) {
            throw new Exception("El número de serie es obligatorio");
        }

        // Verificar si el número de serie ya existe (excluyendo la impresora actual)
        $stmt = $pdo->prepare("SELECT id FROM impresoras WHERE numero_serie = ? AND id != ?");
        $stmt->execute([$numero_serie, $impresora_id]);
        if ($stmt->fetch()) {
            throw new Exception("Ya existe otra impresora con este número de serie");
        }

        // Determinar fecha de asignación
        $fecha_asignacion = $impresora['fecha_asignacion'];
        if ($usuario_id && !$impresora['usuario_id']) {
            // Si se está asignando por primera vez
            $fecha_asignacion = date('Y-m-d');
        } elseif (!$usuario_id && $impresora['usuario_id']) {
            // Si se está desasignando
            $fecha_asignacion = null;
        }

        // Actualizar la impresora
        $stmt = $pdo->prepare("
            UPDATE impresoras 
            SET marca = ?, modelo = ?, numero_serie = ?, contrasena = ?, 
                usuario_id = ?, departamento = ?, estado = ?, 
                fecha_asignacion = ?, observaciones = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $marca,
            $modelo,
            $numero_serie,
            $contrasena,
            $usuario_id,
            $departamento,
            $estado,
            $fecha_asignacion,
            $observaciones,
            $impresora_id
        ]);

        // Registrar auditoría
        if (isset($usuario_id)) {
            registrar_auditoria($pdo, $usuario_id, 'ACTUALIZAR', "Se actualizó la impresora $marca $modelo con serie $numero_serie", 'impresoras', $impresora_id);
        }

        $_SESSION['success'] = "Impresora actualizada exitosamente";
        header("Location: impresoras_list.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

require_once 'header.php';
?>

<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div><h4 class="mb-3">Editar Impresora</h4></div>
                <a href="impresoras_list.php" class="btn btn-secondary">
                    <i class="las la-arrow-left mr-2"></i> Volver a la lista
                </a>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Información de la Impresora</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
                            <!-- Marca -->
                            <div class="col-md-6 mb-3">
                                <label for="marca" class="form-label">Marca *</label>
                                <input type="text" class="form-control" id="marca" name="marca" 
                                       value="<?= htmlspecialchars($_POST['marca'] ?? $impresora['marca']); ?>" required>
                            </div>

                            <!-- Modelo -->
                            <div class="col-md-6 mb-3">
                                <label for="modelo" class="form-label">Modelo *</label>
                                <input type="text" class="form-control" id="modelo" name="modelo" 
                                       value="<?= htmlspecialchars($_POST['modelo'] ?? $impresora['modelo']); ?>" required>
                            </div>

                            <!-- Número de Serie -->
                            <div class="col-md-6 mb-3">
                                <label for="numero_serie" class="form-label">Número de Serie *</label>
                                <input type="text" class="form-control" id="numero_serie" name="numero_serie" 
                                       value="<?= htmlspecialchars($_POST['numero_serie'] ?? $impresora['numero_serie']); ?>" required>
                            </div>

                            <!-- Contraseña -->
                            <div class="col-md-6 mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input type="text" class="form-control" id="contrasena" name="contrasena" 
                                       value="<?= htmlspecialchars($_POST['contrasena'] ?? $impresora['contrasena']); ?>">
                            </div>

                            <!-- Usuario Asignado -->
                            <div class="col-md-6 mb-3">
                                <label for="usuario_id" class="form-label">Usuario Asignado</label>
                                <select class="form-control" id="usuario_id" name="usuario_id">
                                    <option value="">Seleccionar usuario...</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= $usuario['id'] ?>" 
                                                <?= (($_POST['usuario_id'] ?? $impresora['usuario_id']) == $usuario['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario['primer_nombre'] . ' ' . $usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Departamento -->
                            <div class="col-md-6 mb-3">
                                <label for="departamento" class="form-label">Departamento</label>
                                <input type="text" class="form-control" id="departamento" name="departamento" 
                                       value="<?= htmlspecialchars($_POST['departamento'] ?? $impresora['departamento']); ?>">
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option value="En Stock" <?= (($_POST['estado'] ?? $impresora['estado']) == 'En Stock') ? 'selected' : ''; ?>>En Stock</option>
                                    <option value="En uso" <?= (($_POST['estado'] ?? $impresora['estado']) == 'En uso') ? 'selected' : ''; ?>>En uso</option>
                                    <option value="En mantenimiento" <?= (($_POST['estado'] ?? $impresora['estado']) == 'En mantenimiento') ? 'selected' : ''; ?>>En mantenimiento</option>
                                    <option value="Fuera de servicio" <?= (($_POST['estado'] ?? $impresora['estado']) == 'Fuera de servicio') ? 'selected' : ''; ?>>Fuera de servicio</option>
                                </select>
                            </div>

                            <!-- Observaciones -->
                            <div class="col-md-12 mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?= htmlspecialchars($_POST['observaciones'] ?? $impresora['observaciones']); ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="las la-save mr-2"></i> Actualizar Impresora
                                </button>
                                <a href="impresoras_list.php" class="btn btn-secondary">
                                    <i class="las la-times mr-2"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?> 