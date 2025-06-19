<?php
session_start();
require_once '../config.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$id = $_GET['id'];
$mensaje_error = "";

// Verificamos si se ha enviado el formulario de confirmación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirmacion = $_POST['confirmacion'];

    if ($confirmacion === 'ELIMINAR') {
        // Realizamos la eliminación del usuario
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        // Redirigimos después de la eliminación
        echo "<script>; window.location.href = 'usuarios_list.php';</script>";
        exit;
    } else {
        // Si la confirmación no es correcta, mostramos un mensaje de error
        $mensaje_error = "La confirmación no coincide. Escribe exactamente 'ELIMINAR' para continuar.";
    }
}
?>

<!-- Estilos para el formulario -->
<style>
    .container { max-width: 600px; margin-top: 30px; }
    .card { border-radius: 12px; }
    .form-group { margin-bottom: 15px; }
</style>

<!-- Contenido de la página -->
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
            <h5 class="mb-0">Eliminar Usuario</h5>
        </div>
        <div class="card-body">
            <?php if ($mensaje_error): ?>
                <div class="alert alert-danger"><?= $mensaje_error ?></div>
            <?php endif; ?>
            <p><strong>Advertencia:</strong> Esta acción no se puede deshacer. Para confirmar la eliminación del usuario, escribe la palabra <strong>ELIMINAR</strong> en el campo a continuación.</p>
            <form method="POST">
                <div class="form-group">
                    <label for="confirmacion">Escribe "ELIMINAR" para confirmar:</label>
                    <input type="text" class="form-control" id="confirmacion" name="confirmacion" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <a href="usuarios_list.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
