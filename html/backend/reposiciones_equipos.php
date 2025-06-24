<?php
require_once '../config.php';
require_once 'header.php';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_POST['equipo_id'], $_POST['nuevo_equipo_id'])
) {
    $equipo_actual_id = (int)$_POST['equipo_id'];
    $nuevo_equipo_id = (int)$_POST['nuevo_equipo_id'];
    $superusuario_id = $_SESSION['superusuario']['id'] ?? null;

    // 1. Obtener usuario asignado y su nombre ANTES de desvincular
    $stmt = $pdo->prepare("SELECT usuario FROM equipo_computo WHERE id = ?");
    $stmt->execute([$equipo_actual_id]);
    $usuario_id = $stmt->fetchColumn();

    $nombre_usuario = 'Sin asignar';
    if ($usuario_id) {
        $stmtNombre = $pdo->prepare("SELECT primer_nombre, apellido_paterno FROM usuarios WHERE id = ?");
        $stmtNombre->execute([$usuario_id]);
        $row = $stmtNombre->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $nombre_usuario = $row['primer_nombre'] . ' ' . $row['apellido_paterno'];
        }
    }

    // 2. Obtener datos del equipo anterior
    $stmtEquipo = $pdo->prepare("SELECT nombre_pc, marca_pc, modelo_pc, serial_pc FROM equipo_computo WHERE id = ?");
    $stmtEquipo->execute([$equipo_actual_id]);
    $equipoAnterior = $stmtEquipo->fetch(PDO::FETCH_ASSOC);

    $infoEquipoAnterior = '';
    if ($equipoAnterior) {
        $infoEquipoAnterior = "{$equipoAnterior['nombre_pc']} | {$equipoAnterior['marca_pc']} | {$equipoAnterior['modelo_pc']} | {$equipoAnterior['serial_pc']}";
    }

    // 3. Desvincular equipo anterior (esto debe ir DESPUÉS de obtener usuario y nombre)
    $stmt = $pdo->prepare("UPDATE equipo_computo SET usuario = NULL, asignacion = NULL WHERE id = ?");
    $stmt->execute([$equipo_actual_id]);

    // 4. Asignar nuevo equipo
    $stmt = $pdo->prepare("UPDATE equipo_computo SET usuario = ?, asignacion = NOW() WHERE id = ?");
    $stmt->execute([$usuario_id, $nuevo_equipo_id]);

    // 5. Obtener datos del nuevo equipo
    $stmtEquipo = $pdo->prepare("SELECT nombre_pc, marca_pc, modelo_pc, serial_pc FROM equipo_computo WHERE id = ?");
    $stmtEquipo->execute([$nuevo_equipo_id]);
    $equipoNuevo = $stmtEquipo->fetch(PDO::FETCH_ASSOC);

    $infoEquipoNuevo = '';
    if ($equipoNuevo) {
        $infoEquipoNuevo = "{$equipoNuevo['nombre_pc']} | {$equipoNuevo['marca_pc']} | {$equipoNuevo['modelo_pc']} | {$equipoNuevo['serial_pc']}";
    }

    // 6. Descripciones enriquecidas
    $desc_desvincular = "Desvinculó el equipo ID $equipo_actual_id ($infoEquipoAnterior) del usuario ID $usuario_id ($nombre_usuario)";
    $desc_asignar = "Asignó el equipo ID $nuevo_equipo_id ($infoEquipoNuevo) al usuario ID $usuario_id ($nombre_usuario), reposicionando el equipo anterior ID $equipo_actual_id ($infoEquipoAnterior)";

    // 7. Registrar auditoría
    registrar_auditoria($pdo, $superusuario_id, 'reposicion', $desc_desvincular, 'equipo_computo', $equipo_actual_id);
    registrar_auditoria($pdo, $superusuario_id, 'reposicion', $desc_asignar, 'equipo_computo', $nuevo_equipo_id);

    $mensaje = 'Reposición realizada correctamente. El usuario ha sido asignado al nuevo equipo.';
}

$sql = "SELECT e.id, e.nombre_pc, e.marca_pc, e.serial_pc, e.modelo_pc, e.estado_pc, e.usuario,
               u.primer_nombre, u.apellido_paterno,
               s.nombre AS super_nombre, s.apellido AS super_apellido
        FROM equipo_computo e
        LEFT JOIN usuarios u ON e.usuario = u.id
        LEFT JOIN superusuarios s ON e.usuario = s.id
        ORDER BY e.id DESC";
$stmt = $pdo->query($sql);
$equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reposiciones de Equipos</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendor/select2/css/select2.min.css">
    <style>
        .container { max-width: 1200px; margin-top: 40px; }
        .table th, .table td { font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <h3 class="mb-4">Reposiciones de Equipos</h3>
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success"> <?= htmlspecialchars($mensaje) ?> </div>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre PC</th>
                    <th>Marca</th>
                    <th>Serial</th>
                    <th>Modelo</th>
                    <th>Estado</th>
                    <th>Usuario Asignado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipos as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['id']) ?></td>
                        <td><?= htmlspecialchars($e['nombre_pc']) ?></td>
                        <td><?= htmlspecialchars($e['marca_pc']) ?></td>
                        <td><?= htmlspecialchars($e['serial_pc']) ?></td>
                        <td><?= htmlspecialchars($e['modelo_pc']) ?></td>
                        <td><?= htmlspecialchars($e['estado_pc']) ?></td>
                        <td>
                            <?php
                                if (!empty($e['primer_nombre'])) {
                                    echo htmlspecialchars($e['primer_nombre'] . ' ' . $e['apellido_paterno']);
                                } elseif (!empty($e['super_nombre'])) {
                                    echo htmlspecialchars($e['super_nombre'] . ' ' . $e['super_apellido']);
                                } else {
                                    echo 'Sin asignar';
                                }
                            ?>
                        </td>
                        <td>
                            <button 
                                type="button" 
                                class="btn btn-warning btn-sm btn-reposicion" 
                                data-toggle="modal" 
                                data-target="#reposicionModal"
                                data-equipo-id="<?= htmlspecialchars($e['id']) ?>"
                                data-usuario-id="<?= htmlspecialchars($e['usuario']) ?>"
                            >
                                <i class="fas fa-exchange-alt"></i> Reposición
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Reposición -->
<div class="modal fade" id="reposicionModal" tabindex="-1" role="dialog" aria-labelledby="reposicionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" id="formReposicion">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reposicionModalLabel">Reposición de Equipo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="equipo_id" id="modal_equipo_id">
          <div class="form-group">
            <label for="nuevo_equipo_id"><strong>Selecciona el nuevo equipo a asignar:</strong></label>
            <select name="nuevo_equipo_id" id="modal_nuevo_equipo_id" class="form-control select2" required>
              <option value="">-- Selecciona un equipo --</option>
              <?php foreach ($equipos as $eq): ?>
                <?php if (empty($eq['usuario'])): // Solo equipos no asignados ?>
                  <option value="<?= htmlspecialchars($eq['id']) ?>">
                    <?= htmlspecialchars($eq['nombre_pc'] . ' | ' . $eq['marca_pc'] . ' | ' . $eq['serial_pc'] . ' | ' . $eq['modelo_pc']) ?>
                  </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Reasignar Equipo</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/select2/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#modal_nuevo_equipo_id').select2({
        dropdownParent: $('#reposicionModal')
    });

    // Al abrir el modal, coloca el ID del equipo en el input oculto
    $('.btn-reposicion').on('click', function() {
        var equipoId = $(this).data('equipo-id');
        $('#modal_equipo_id').val(equipoId);
        // Opcional: puedes resetear el select
        $('#modal_nuevo_equipo_id').val('').trigger('change');
    });
});
</script>
</body>
</html> 