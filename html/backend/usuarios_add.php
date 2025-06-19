<?php
session_start();
require_once '../config.php';
require_once 'header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'] ?? null;
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo_corporativo = strtolower($_POST['correo_corporativo']) . "@grupolibera.mx";

    if (empty($primer_nombre) || empty($apellido_paterno) || empty($correo_corporativo)) {
        $errors[] = "Los campos obligatorios deben completarse.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO usuarios (primer_nombre, segundo_nombre, apellido_paterno, apellido_materno, correo_corporativo) 
        VALUES (:primer_nombre, :segundo_nombre, :apellido_paterno, :apellido_materno, :correo_corporativo)");

        $stmt->execute([
            'primer_nombre' => $primer_nombre,
            'segundo_nombre' => $segundo_nombre,
            'apellido_paterno' => $apellido_paterno,
            'apellido_materno' => $apellido_materno,
            'correo_corporativo' => $correo_corporativo
        ]);

        echo "<script> window.location.href = 'usuarios_list.php';</script>";
        exit();
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Registrar Usuario</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Primer Nombre</label>
                                <input type="text" name="primer_nombre" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Apellido Paterno</label>
                                <input type="text" name="apellido_paterno" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Apellido Materno</label>
                                <input type="text" name="apellido_materno" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Correo Corporativo</label>
                            <div class="input-group">
                                <input type="text" name="correo_corporativo" class="form-control" placeholder="usuario">
                                <div class="input-group-append">
                                    <span class="input-group-text">@grupolibera.mx</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-3">Registrar Usuario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
