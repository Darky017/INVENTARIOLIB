<?php
ob_start();
session_start();
require_once '../config.php';
require_once 'header.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir datos
    $solicitud_alta = $_POST['solicitud_alta'] ?? null;
    $zona = trim($_POST['zona'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $primer_nombre = trim($_POST['primer_nombre'] ?? '');
    $segundo_nombre = trim($_POST['segundo_nombre'] ?? '');
    $primer_apellido = trim($_POST['primer_apellido'] ?? '');
    $segundo_apellido = trim($_POST['segundo_apellido'] ?? '');
    $correo_personal = trim($_POST['correo_personal'] ?? '');
    $n_tel = trim($_POST['n_tel'] ?? '');
    $usuario_corporativo = trim($_POST['usuario_corporativo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    // Validar campos obligatorios
    if (empty($zona) || empty($primer_nombre) || empty($primer_apellido) || empty($usuario_corporativo) || empty($contrasena)) {
        $errors[] = "Todos los campos obligatorios deben completarse.";
    }

    // Generar correo corporativo completo
    $correo_corporativo = $usuario_corporativo . "@ventasgrupolibera.mx";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO asesores (solicitud_alta, zona, tipo, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo_personal, n_tel, correo_corporativo, contrasena)
                VALUES (:solicitud_alta, :zona, :tipo, :primer_nombre, :segundo_nombre, :primer_apellido, :segundo_apellido, :correo_personal, :n_tel, :correo_corporativo, :contrasena)");

            $stmt->execute([
                ':solicitud_alta' => $solicitud_alta ?: null,
                ':zona' => $zona,
                ':tipo' => $tipo ?: 'Asesor',
                ':primer_nombre' => $primer_nombre,
                ':segundo_nombre' => $segundo_nombre ?: null,
                ':primer_apellido' => $primer_apellido,
                ':segundo_apellido' => $segundo_apellido ?: null,
                ':correo_personal' => $correo_personal ?: null,
                ':n_tel' => $n_tel ?: null,
                ':correo_corporativo' => $correo_corporativo,
                ':contrasena' => $contrasena
            ]);

            header("Location: asesores_list.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Error al insertar en la base de datos: " . $e->getMessage();
        }
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Asesor</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container { max-width: 500px; }
        .card { margin-top: 30px; }
        .btn-primary { background-color: #00813a; border-color: #00813a; }
        .btn-primary:hover { background-color: #006d32; border-color: #006d32; }
        .input-group-text { background-color: #f8f9fa; border-left: none; }
        .input-group input { border-right: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white text-center">
            <h4>Registrar Asesor</h4>
        </div>
        <div class="card-body">
            <p class="text-center">Rellena los datos para registrar un nuevo asesor.</p>
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
                    <input type="date" name="solicitud_alta" class="form-control">
                </div>

                <div class="form-group">
                    <label>Zona <span class="text-danger">*</span></label>
                    <select name="zona" class="form-control" required>
                        <option value="">Selecciona una zona</option>
                        <option>Mérida</option>
                        <option>Mérida 2</option>
                        <option>Ciudad de México</option>
                        <option>Estado de México</option>
                        <option>Bajío</option>
                        <option>Guadalajara</option>
                        <option>Monterrey</option>
                        <option>Real State</option>
                        <option>Caribe</option>
                        <option>Santuario</option>
                        <option>Vertere</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <input type="text" name="tipo" class="form-control" value="Asesor">
                </div>

                <div class="form-group">
                    <label>Primer Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="primer_nombre" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" class="form-control">
                </div>

                <div class="form-group">
                    <label>Primer Apellido <span class="text-danger">*</span></label>
                    <input type="text" name="primer_apellido" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" class="form-control">
                </div>

                <div class="form-group">
                    <label>Correo Personal</label>
                    <input type="email" name="correo_personal" class="form-control">
                </div>

                <div class="form-group">
                    <label>Numero Personal</label>
                    <input type="text" name="n_tel" class="form-control">
                </div>

                <div class="form-group">
                    <label>Correo Corporativo <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="usuario_corporativo" class="form-control" required>
                        <div class="input-group-append">
                            <span class="input-group-text">@ventasgrupolibera.mx</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Contraseña <span class="text-danger">*</span></label>
                    <input type="text" name="contrasena" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Registrar Asesor</button>
            </form>
        </div>
    </div>
</div>

<script src="../assets/js/backend-bundle.min.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>
