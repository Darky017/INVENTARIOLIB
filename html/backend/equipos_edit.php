<?php
session_start();
require_once '../config.php';
require_once 'funciones_historial.php';

// Verificar si se recibió un ID válido
if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = $_GET['id'];

// Obtener los datos del equipo
$stmt = $pdo->prepare("SELECT * FROM equipo_computo WHERE id = :id");
$stmt->execute(['id' => $id]);
$equipo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$equipo) {
    die("Equipo no encontrado.");
}

// Obtener lista de usuarios con la columna correcta
$stmtUsuarios = $pdo->query("SELECT id, CONCAT(primer_nombre, ' ', apellido_paterno) AS nombre FROM usuarios");
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_POST['usuario'] ?? null;
    $departamento = $_POST['departamento'] ?? null;
    $asignacion = $_POST['asignacion'];
    $nombre_pc = $_POST['nombre_pc'];
    $marca_pc = $_POST['marca_pc'];
    $modelo_pc = $_POST['modelo_pc'];
    $serial_pc = $_POST['serial_pc'];
    $estado_pc = $_POST['estado_pc'];

    if (empty($nombre_pc) || empty($marca_pc) || empty($modelo_pc) || empty($serial_pc)) {
        $errors[] = "Todos los campos son obligatorios.";
    }

    if (empty($errors)) {
        // Guardar usuario anterior para comparar
        $usuario_anterior = $equipo['usuario'];
        $superusuario_id = $_SESSION['superusuario']['id'] ?? null;

        $stmt = $pdo->prepare("UPDATE equipo_computo SET 
            usuario = :usuario, 
            departamento = :departamento,
            asignacion = :asignacion,
            nombre_pc = :nombre_pc, 
            marca_pc = :marca_pc, 
            modelo_pc = :modelo_pc, 
            serial_pc = :serial_pc, 
            estado_pc = :estado_pc
            WHERE id = :id");

        $stmt->execute([
            'usuario' => $usuario_id,
            'departamento' => $departamento,
            'asignacion' => $asignacion,
            'nombre_pc' => $nombre_pc,
            'marca_pc' => $marca_pc,
            'modelo_pc' => $modelo_pc,
            'serial_pc' => $serial_pc,
            'estado_pc' => $estado_pc,
            'id' => $id
        ]);

        // Manejar cambios en el historial de asignaciones
        if ($usuario_anterior != $usuario_id) {
            // Si había un usuario asignado anteriormente, registrar la desasignación
            if (!empty($usuario_anterior)) {
                registrar_desasignacion($pdo, $id, 'computo', 'Cambio de asignación', $superusuario_id);
            }
            
            // Si se asignó un nuevo usuario, registrar la nueva asignación
            if (!empty($usuario_id)) {
                registrar_asignacion(
                    $pdo, 
                    $id, 
                    $usuario_id, 
                    'computo', 
                    $departamento, 
                    'Reasignación de equipo', 
                    $superusuario_id
                );
            }
        }

        if ($estado_pc == 'Repuesto' || $estado_pc == 'Reemplazado') {
            registrar_auditoria($pdo, $superusuario_id, 'reposicion', 'Cambio de estado a ' . $estado_pc, 'equipo_computo', $id);
        }

        header("Location: equipos_list.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Equipo</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 600px;
        }
        .card {
            margin-top: 40px;
        }
        .btn-primary {
            background-color: #00813a;
            border-color: #00813a;
        }
        .btn-primary:hover {
            background-color: #006d32;
            border-color: #006d32;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4>Editar Equipo</h4>
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
                        <label><strong>Usuario Asignado:</strong></label>
                        <select name="usuario" class="form-control">
                            <option value="">Sin Asignar</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo $usuario['id']; ?>" <?php echo ($usuario['id'] == $equipo['usuario']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($usuario['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><strong>Departamento:</strong></label>
                        <input type="text" name="departamento" class="form-control" value="<?php echo htmlspecialchars($equipo['departamento']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><strong>Nombre del Equipo:</strong></label>
                        <input type="text" name="nombre_pc" class="form-control" value="<?php echo htmlspecialchars($equipo['nombre_pc']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><strong>Fecha de Asignación:</strong></label>
                        <input type="date" name="asignacion" class="form-control" value="<?php echo htmlspecialchars($equipo['asignacion']); ?>" optional>

                    <div class="form-group">
                        <label><strong>Marca:</strong></label>
                        <input type="text" name="marca_pc" class="form-control" value="<?php echo htmlspecialchars($equipo['marca_pc']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><strong>Modelo:</strong></label>
                        <input type="text" name="modelo_pc" class="form-control" value="<?php echo htmlspecialchars($equipo['modelo_pc']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><strong>Serie:</strong></label>
                        <input type="text" name="serial_pc" class="form-control" value="<?php echo htmlspecialchars($equipo['serial_pc']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><strong>Estado del Equipo:</strong></label>
                        <select name="estado_pc" class="form-control">
                            <option value="En uso" <?php echo ($equipo['estado_pc'] == 'En uso') ? 'selected' : ''; ?>>En uso</option>
                            <option value="En Stock" <?php echo ($equipo['estado_pc'] == 'En Stock') ? 'selected' : ''; ?>>En Stock</option>
                            <option value="En Reparación" <?php echo ($equipo['estado_pc'] == 'En Reparación') ? 'selected' : ''; ?>>En Reparación</option>
                            <option value="Presenta Fallas" <?php echo ($equipo['estado_pc'] == 'Presenta Fallas') ? 'selected' : ''; ?>>Presenta Fallas</option>
                            <option value="En Garantía" <?php echo ($equipo['estado_pc'] == 'En Garantía') ? 'selected' : ''; ?>>En Garantía</option>
                            <option value="Vendida" <?php echo ($equipo['estado_pc'] == 'Vendida') ? 'selected' : ''; ?>>Vendida</option>
                            <option value="En Proceso de Venta" <?php echo ($equipo['estado_pc'] == 'En Proceso de Venta') ? 'selected' : ''; ?>>En Proceso de Venta</option>
                            <option value="En Baja" <?php echo ($equipo['estado_pc'] == 'En Baja') ? 'selected' : ''; ?>>En Baja</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Actualizar Equipo</button>
                    <a href="equipos_list.php" class="btn btn-secondary btn-block">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
