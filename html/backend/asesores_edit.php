<?php
session_start();
require_once '../config.php';

// Verificar si se recibió un ID válido
if (!isset($_GET['id'])) {
    header("Location: asesores_list.php");
    exit();
}

$id = $_GET['id'];

// Obtener los datos actuales del asesor
$stmt = $pdo->prepare("SELECT * FROM asesores WHERE id = :id");
$stmt->execute(['id' => $id]);
$asesor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asesor) {
    die("Asesor no encontrado.");
}

$errors = [];

// Manejar la actualización del asesor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $solicitud_alta = $_POST['solicitud_alta'];
    $zona = $_POST['zona'];
    $tipo = $_POST['tipo'];
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $correo_personal = $_POST['correo_personal'];
    $n_tel = $_POST['n_tel'];
    $correo_corporativo = $_POST['correo_corporativo'];
    $contrasena = $_POST['contrasena']; // Guardar en texto plano como lo especificaste

    if (empty($zona) || empty($primer_nombre) || empty($primer_apellido) || empty($correo_corporativo) || empty($contrasena)) {
        $errors[] = "Todos los campos obligatorios deben completarse.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE asesores SET 
            solicitud_alta = :solicitud_alta,
            zona = :zona,
            tipo = :tipo,
            primer_nombre = :primer_nombre,
            segundo_nombre = :segundo_nombre,
            primer_apellido = :primer_apellido,
            segundo_apellido = :segundo_apellido,
            correo_personal = :correo_personal,
            n_tel = :n_tel,
            correo_corporativo = :correo_corporativo,
            contrasena = :contrasena
            WHERE id = :id");

        $stmt->execute([
            'solicitud_alta' => $solicitud_alta,
            'zona' => $zona,
            'tipo' => $tipo,
            'primer_nombre' => $primer_nombre,
            'segundo_nombre' => $segundo_nombre,
            'primer_apellido' => $primer_apellido,
            'segundo_apellido' => $segundo_apellido,
            'correo_personal' => $correo_personal,
            'n_tel' => $n_tel,
            'correo_corporativo' => $correo_corporativo,
            'contrasena' => $contrasena,
            'id' => $id
        ]);

        header("Location: asesores_list.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Asesor</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Editar Asesor</h4>
                </div>
                <div class="card-body">
                    <p class="text-center">Modifica los datos del asesor.</p>
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
                            <label>Solicitud de Alta</label>
                            <input type="date" name="solicitud_alta" class="form-control" value="<?php echo htmlspecialchars($asesor['solicitud_alta']); ?>" optional>
                        </div>
                        
                        <div class="form-group">
                            <label>Zona</label>
                            <select name="zona" class="form-control" required>
                                <option value="">Selecciona una zona</option>
                                <option value="Mérida" <?php echo ($asesor['zona'] == "Mérida") ? 'selected' : ''; ?>>Mérida</option>
                                <option value="Mérida 2" <?php echo ($asesor['zona'] == "Mérida 2") ? 'selected' : ''; ?>>Mérida 2</option>
                                <option value="Ciudad de México" <?php echo ($asesor['zona'] == "Ciudad de México") ? 'selected' : ''; ?>>Ciudad de México</option>
                                <option value="Estado de México" <?php echo ($asesor['zona'] == "Estado de México") ? 'selected' : ''; ?>>Estado de México</option>
                                <option value="Bajío" <?php echo ($asesor['zona'] == "Bajío") ? 'selected' : ''; ?>>Bajío</option>
                                <option value="Guadalajara" <?php echo ($asesor['zona'] == "Guadalajara") ? 'selected' : ''; ?>>Guadalajara</option>
                                <option value="Monterrey" <?php echo ($asesor['zona'] == "Monterrey") ? 'selected' : ''; ?>>Monterrey</option>
                                <option value="Real State" <?php echo ($asesor['zona'] == "Real State") ? 'selected' : ''; ?>>Real State</option>
                                <option value="Caribe" <?php echo ($asesor['zona'] == "Caribe") ? 'selected' : ''; ?>>Caribe</option>
                                <option value="Santuario" <?php echo ($asesor['zona'] == "Santuario") ? 'selected' : ''; ?>>Santuario</option>
                                <option value="Vertere" <?php echo ($asesor['zona'] == "Vertere") ? 'selected' : ''; ?>>Vertere</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tipo</label>
                            <input type="text" name="tipo" class="form-control" value="<?php echo htmlspecialchars($asesor['tipo']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Primer Nombre</label>
                            <input type="text" name="primer_nombre" class="form-control" value="<?php echo htmlspecialchars($asesor['primer_nombre']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Segundo Nombre</label>
                            <input type="text" name="segundo_nombre" class="form-control" value="<?php echo htmlspecialchars($asesor['segundo_nombre']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Primer Apellido</label>
                            <input type="text" name="primer_apellido" class="form-control" value="<?php echo htmlspecialchars($asesor['primer_apellido']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Segundo Apellido</label>
                            <input type="text" name="segundo_apellido" class="form-control" value="<?php echo htmlspecialchars($asesor['segundo_apellido']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Correo Personal</label>
                            <input type="email" name="correo_personal" class="form-control" value="<?php echo htmlspecialchars($asesor['correo_personal']); ?>" optional>
                        </div>
                        <div class="form-group">
                            <label>Número de Teléfono</label>
                            <input type="text" name="n_tel" class="form-control" value="<?php echo htmlspecialchars($asesor['n_tel']); ?>" optional>    
                        </div>
                        <div class="form-group">
                            <label>Correo Corporativo</label>
                            <input type="email" name="correo_corporativo" class="form-control" value="<?php echo htmlspecialchars($asesor['correo_corporativo']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Contraseña</label>
                            <input type="text" name="contrasena" class="form-control" value="<?php echo htmlspecialchars($asesor['contrasena']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
                        <a href="asesores_list.php" class="btn btn-secondary btn-block">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/backend-bundle.min.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>
