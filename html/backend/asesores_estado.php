<?php
ob_start(); // Iniciar el buffer de salida
session_start();
require_once '../config.php';
require_once 'header.php';
date_default_timezone_set('America/Mexico_City');

if (!isset($_GET['id'])) {
    header("Location: asesores_list.php");
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM asesores WHERE id = :id");
$stmt->execute(['id' => $id]);
$asesor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asesor) {
    die("Asesor no encontrado.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_estado = $_POST['estado'];

    $solicitud_baja = !empty($_POST['fecha_solicitud_baja']) ? date("Y-m-d", strtotime($_POST['fecha_solicitud_baja'])) : $asesor['solicitud_baja'];
    $fecha_suspension = !empty($_POST['fecha_suspension']) ? date("Y-m-d", strtotime($_POST['fecha_suspension'])) : $asesor['fecha_baja'];
    $fecha_proxima_eliminacion = !empty($fecha_suspension) ? date("Y-m-d", strtotime("+1 month", strtotime($fecha_suspension))) : $asesor['fecha_proxima_eliminacion'];
    $fecha_respaldo = !empty($_POST['fecha_respaldo']) ? date("Y-m-d", strtotime($_POST['fecha_respaldo'])) : NULL;
    $fecha_eliminacion = ($nuevo_estado == "Eliminado") ? date("Y-m-d") : NULL;
    $nueva_contrasena = !empty($_POST['nueva_contrasena']) ? $_POST['nueva_contrasena'] : NULL;
    $comentarios = $_POST['comentarios'] ?? '';

    if ($nuevo_estado == "Suspendido") {
        if (empty($_POST['fecha_solicitud_baja']) || empty($_POST['fecha_suspension']) || empty($_POST['nueva_contrasena'])) {
            $errors[] = "Debes ingresar la Fecha de Solicitud de Baja, Fecha de Suspensión y una nueva contraseña.";
        }
    }

    if ($nuevo_estado == "Eliminado") {
        if ($asesor['estado'] != "Suspendido") {
            $errors[] = "Solo se pueden eliminar asesores que estén suspendidos.";
        } elseif (empty($_POST['fecha_respaldo'])) {
            $errors[] = "Debes seleccionar la Fecha de Respaldo antes de eliminar al asesor.";
        }
    }

    if ($nuevo_estado == "Activo") {
        // Si se cambia a Activo, SE BORRAN TODAS LAS FECHAS
        $solicitud_baja = NULL;
        $fecha_suspension = NULL;
        $fecha_proxima_eliminacion = NULL;
        $fecha_respaldo = NULL;
        $fecha_eliminacion = NULL;
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE asesores SET 
            estado = :estado, 
            solicitud_baja = :solicitud_baja,
            fecha_baja = :fecha_suspension,
            fecha_proxima_eliminacion = :fecha_proxima_eliminacion,
            fecha_respaldo = :fecha_respaldo,
            fecha_eliminacion = :fecha_eliminacion,
            comentarios = :comentarios,
            contrasena = CASE WHEN :estado = 'Suspendido' THEN :nueva_contrasena ELSE contrasena END
            WHERE id = :id");

        $stmt->execute([
            'estado' => $nuevo_estado,
            'solicitud_baja' => $solicitud_baja,
            'fecha_suspension' => $fecha_suspension,
            'fecha_proxima_eliminacion' => $fecha_proxima_eliminacion,
            'fecha_respaldo' => $fecha_respaldo,
            'fecha_eliminacion' => $fecha_eliminacion,
            'comentarios' => $comentarios,
            'nueva_contrasena' => $nueva_contrasena,
            'id' => $id
        ]);

        header("Location: asesores_list.php");
        exit();
    }
}
ob_end_flush(); // Enviar buffer de salida
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Estado del Asesor</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <style>
        .container { max-width: 500px; }
        .card { margin-top: 30px; }
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white text-center">
            <h5>Cambiar Estado del Asesor</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label><strong>Nombre:</strong></label>
                    <p><?php echo htmlspecialchars($asesor['primer_nombre'] . ' ' . $asesor['primer_apellido']); ?></p>
                </div>
                <div class="form-group">
                    <label><strong>Estado Actual:</strong></label>
                    <p><?php echo htmlspecialchars($asesor['estado']); ?></p>
                </div>
                <div class="form-group">
                    <label><strong>Nuevo Estado:</strong></label>
                    <select id="estado" name="estado" class="form-control" required>
                        <option value="Activo">Activo</option>
                        <option value="Suspendido">Suspendido</option>
                        <option value="Eliminado">Eliminado</option>
                    </select>
                </div>

                <!-- Campos para Suspendido -->
                <div id="suspendido-fields" class="hidden">
                    <div class="form-group">
                        <label><strong>Contraseña Restablecida:</strong></label>
                        <input type="text" name="nueva_contrasena" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong>Fecha de Solicitud de Baja:</strong></label>
                        <input type="date" name="fecha_solicitud_baja" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong>Fecha de Suspensión:</strong></label>
                        <input type="date" id="fecha_suspension" name="fecha_suspension" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong>Fecha Próxima Eliminación:</strong></label>
                        <input type="date" id="fecha_proxima_eliminacion" name="fecha_proxima_eliminacion" class="form-control" readonly>
                    </div>
                </div>

                <!-- Campos para Eliminado -->
                <div id="eliminado-fields" class="hidden">
                    <div class="form-group">
                        <label><strong>Fecha de Respaldo:</strong></label>
                        <input type="date" name="fecha_respaldo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong>Fecha de Eliminación:</strong></label>
                        <input type="date" name="fecha_eliminacion" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label><strong>Comentarios:</strong></label>
                        <textarea name="comentarios" class="form-control"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Actualizar Estado</button>
                <a href="asesores_list.php" class="btn btn-secondary btn-block">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById("estado").addEventListener("change", function() {
    document.getElementById("suspendido-fields").style.display = (this.value === "Suspendido") ? "block" : "none";
    document.getElementById("eliminado-fields").style.display = (this.value === "Eliminado") ? "block" : "none";
});
</script>

</body>
</html>
