<?php
session_start();
require_once '../config.php';
require_once 'header.php';

$errors = [];
$usuario = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "<script>alert('Usuario no encontrado'); window.location.href = 'usuarios_list.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID inválido'); window.location.href = 'usuarios_list.php';</script>";
    exit();
}

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
        $stmt = $pdo->prepare("UPDATE usuarios SET primer_nombre = :primer_nombre, segundo_nombre = :segundo_nombre, 
            apellido_paterno = :apellido_paterno, apellido_materno = :apellido_materno, correo_corporativo = :correo_corporativo WHERE id = :id");

        $stmt->execute([
            'primer_nombre' => $primer_nombre,
            'segundo_nombre' => $segundo_nombre,
            'apellido_paterno' => $apellido_paterno,
            'apellido_materno' => $apellido_materno,
            'correo_corporativo' => $correo_corporativo,
            'id' => $_GET['id']
        ]);

        echo "<script>window.location.href = 'usuarios_list.php';</script>";
        exit();
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Editar Usuario</h4>
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
                                <input type="text" name="primer_nombre" class="form-control" value="<?= htmlspecialchars($usuario['primer_nombre']) ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" class="form-control" value="<?= htmlspecialchars($usuario['segundo_nombre']) ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Apellido Paterno</label>
                                <input type="text" name="apellido_paterno" class="form-control" value="<?= htmlspecialchars($usuario['apellido_paterno']) ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Apellido Materno</label>
                                <input type="text" name="apellido_materno" class="form-control" value="<?= htmlspecialchars($usuario['apellido_materno']) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Correo Corporativo</label>
                            <div class="input-group">
                                <input type="text" name="correo_corporativo" class="form-control" value="<?= htmlspecialchars(explode('@', $usuario['correo_corporativo'])[0]) ?>">
                                <div class="input-group-append">
                                    <span class="input-group-text">@grupolibera.mx</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-3">Actualizar Usuario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>