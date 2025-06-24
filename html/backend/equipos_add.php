<?php
ob_start(); // Inicia el buffer de salida
session_start();

require_once '../config.php';
require_once 'header.php';
require_once 'funciones_historial.php';

// Obtener usuarios para el combobox
$stmt = $pdo->query("SELECT id, primer_nombre, apellido_paterno FROM usuarios ORDER BY primer_nombre");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ... (código PHP anterior) ...

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_asignado = $_POST['usuario_asignado'] ?? null;
    $asignacion = $_POST['asignacion'] ?? null;
    $propiedad = $_POST['propiedad'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $nombre_pc = $_POST['nombre_pc'] ?? '';
    $marca_pc = $_POST['marca_pc'] ?? '';
    $serial_pc = $_POST['serial_pc'] ?? '';
    $modelo_pc = $_POST['modelo_pc'] ?? '';
    $estado_pc = $_POST['estado_pc'] ?? '';

    // Validación general
    if (empty($propiedad) || empty($ubicacion) || empty($departamento) || empty($nombre_pc) || empty($marca_pc) || empty($serial_pc) || empty($modelo_pc) || empty($estado_pc)) {
        $errors[] = "Todos los campos obligatorios (excepto Usuario/Fecha Asignación) deben completarse.";
    }

    // --- INICIO: Validación Condicional de Fecha Asignación ---
    if (!empty($usuario_asignado) && empty($asignacion)) {
        // Si se seleccionó un usuario pero no se proporcionó fecha
        $asignacion = null; // O '' dependiendo de tu columna en la BD
    } elseif (empty($usuario_asignado)) {
         // Si no se seleccionó usuario, asegúrate que la fecha sea NULL o vacía para la BD
         $asignacion = null; // O '' dependiendo de tu columna en la BD
    }
    // --- FIN: Validación Condicional de Fecha Asignación ---


    // Verificar si el número de serie ya existe
    if (!empty($serial_pc)) { // Solo verificar si no está vacío
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM equipo_computo WHERE serial_pc = :serial_pc");
        $stmt_check->execute(['serial_pc' => $serial_pc]);
        if ($stmt_check->fetchColumn() > 0) {
            $errors[] = "El número de serie '$serial_pc' ya está registrado.";
        }
    } else {
         $errors[] = "El número de serie es obligatorio."; // Añadir validación si serial_pc es requerido siempre
    }


    // Guardar si no hay errores
    if (empty($errors)) {
        // ... (resto del código para insertar en la BD) ...
        // Asegúrate de pasar el valor correcto de $asignacion (puede ser null si no hay usuario)
         $stmt = $pdo->prepare("INSERT INTO equipo_computo
                (usuario, asignacion, propiedad, ubicacion, departamento, contrasena, nombre_pc, marca_pc, serial_pc, modelo_pc, estado_pc)
                VALUES (:usuario_asignado, :asignacion, :propiedad, :ubicacion, :departamento, :contrasena, :nombre_pc, :marca_pc, :serial_pc, :modelo_pc, :estado_pc)");

         $stmt->execute([
             // Usa null si el valor está vacío, PDO puede manejarlo si la columna acepta NULL
             'usuario_asignado' => !empty($usuario_asignado) ? $usuario_asignado : null,
             'asignacion' => $asignacion, // Ya es null si no hay usuario
             'propiedad' => $propiedad,
             'ubicacion' => $ubicacion,
             'departamento' => $departamento,
             'contrasena' => $contrasena,
             'nombre_pc' => $nombre_pc,
             'marca_pc' => $marca_pc,
             'serial_pc' => $serial_pc,
             'modelo_pc' => $modelo_pc,
             'estado_pc' => $estado_pc
         ]);

         $equipo_id = $pdo->lastInsertId();
         $usuario_id = $_SESSION['superusuario']['id'] ?? null;
         if ($usuario_id !== null) {
             registrar_auditoria($pdo, $usuario_id, 'Añadir Equipo', 'Nuevo Equipo Añadido', 'equipo_computo', $equipo_id);
         }

         // Registrar en el historial si se asignó un usuario
         if (!empty($usuario_asignado)) {
             registrar_asignacion(
                 $pdo, 
                 $equipo_id, 
                 $usuario_asignado, 
                 'computo', 
                 $departamento, 
                 'Asignación inicial del equipo', 
                 $usuario_id
             );
         }

        ob_end_clean(); // Limpiar el buffer antes de la redirección
        header("Location: equipos_list.php");
        exit();
    }
}

// ... (resto del código PHP para mostrar el formulario) ...
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Equipo</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendor/select2/css/select2.min.css">
    <style>
        .container { max-width: 550px; }
        .card { margin-top: 30px; border-radius: 10px; }
        .form-group { margin-bottom: 10px; }
        .btn { padding: 6px 12px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5>Registrar Equipo de Cómputo</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                
              
<div class="form-group">
    <label><strong>Usuario Asignado</strong></label>
                <select name="usuario_asignado" id="usuario_asignado_select" class="form-control select2">
    <option value="">Sin Asignación</option>
    <?php foreach ($usuarios as $usuario): ?>
        <option value="<?= $usuario['id']; ?>">
            <?= htmlspecialchars($usuario['primer_nombre'] . " " . $usuario['apellido_paterno']); ?>
        </option>
    <?php endforeach; ?>
</select>

<div class="form-group" id="fecha_asignacion_div">
    <label><strong>Fecha de Asignación</strong></label>
    <input type="date" name="asignacion" id="asignacion_input" class="form-control">
</div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Propiedad</strong></label>
                        <input type="text" name="propiedad" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>Ubicación</strong></label>
                        <input type="text" name="ubicacion" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Departamento</strong></label>
                        <input type="text" name="departamento" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>Contraseña</strong></label>
                        <input type="text" name="contrasena" class="form-control">
                    </div>
                </div>

                <h6 class="mt-3"><strong>Datos del Equipo</strong></h6>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Nombre</strong></label>
                        <input type="text" name="nombre_pc" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>Marca</strong></label>
                        <input type="text" name="marca_pc" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Serial</strong></label>
                        <input type="text" name="serial_pc" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>Modelo</strong></label>
                        <input type="text" name="modelo_pc" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Estado del Equipo</strong></label>
                    <select name="estado_pc" class="form-control" required>
                        <option value="">Seleccione un estado</option>
                        <option value="En uso">En uso</option>
                        <option value="En Stock">En Stock</option>
                        <option value="En Reparación">En Reparación</option>
                        <option value="Presenta Fallas">Presenta Fallas</option>
                        <option value="En Garantía">En Garantía</option>
                        <option value="Vendida">Vendida</option>
                        <option value="En Proceso de Venta">En Proceso de Venta</option>
                        <option value="En Baja">En Baja</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="equipos_list.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/select2/js/select2.min.js"></script>

<script src="../assets/vendor/jquery/jquery.min.js"></script> <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/select2/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2();

    // --- INICIO: Lógica para mostrar/ocultar fecha de asignación ---
    var usuarioSelect = $('#usuario_asignado_select');
    var fechaAsignacionDiv = $('#fecha_asignacion_div');
    var fechaAsignacionInput = $('#asignacion_input');

    // Función para verificar y mostrar/ocultar el campo de fecha
    function toggleFechaAsignacion() {
        if (usuarioSelect.val() === "") { // Si la opción seleccionada es "Sin Asignación" (valor vacío)
            fechaAsignacionDiv.hide(); // Oculta el div
            fechaAsignacionInput.prop('required', false); // Quita el 'required' del input
        } else { // Si se selecciona un usuario
            fechaAsignacionDiv.show(); // Muestra el div
            fechaAsignacionInput.prop('required', false); // Añade 'required' al input
        }
    }

    // Ejecutar la función al cargar la página para establecer el estado inicial
    toggleFechaAsignacion();

    // Ejecutar la función cada vez que cambie la selección del usuario
    usuarioSelect.on('change', function() {
        toggleFechaAsignacion();
    });
    // --- FIN: Lógica para mostrar/ocultar fecha de asignación ---

});
</script>
<script>
    $(document).ready(function() { $('.select2').select2(); });
</script>

</body>
</html>
