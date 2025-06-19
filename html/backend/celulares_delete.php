<?php
require_once '../config.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    die("ID de celular no proporcionado.");
}

$id = $_GET['id'];
$mensaje_error = "";

// Verificamos si se ha pasado el parámetro de confirmación en la URL
if (isset($_POST['confirmacion']) && $_POST['confirmacion'] === 'ELIMINAR') {
    // Confirmación recibida, procedemos a eliminar el celular
    $stmt = $pdo->prepare("DELETE FROM equipo_celular WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // Redirigimos a la lista de celulares después de eliminar
    echo "<script> window.location.href = 'celulares_list.php';</script>";
    exit; // Asegura que no se ejecute más código
} else {
    // Si no se ha confirmado, mostramos el modal para confirmar
    echo "
    <div class='modal fade' id='modalConfirmacion' tabindex='-1' aria-labelledby='modalConfirmacionLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='modalConfirmacionLabel'>Confirmar Eliminación de Celular</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <strong>¡Advertencia!</strong> Esta acción no se puede deshacer. ¿Estás seguro de que deseas eliminar este celular? Para confirmarlo, escribe <strong>\"ELIMINAR\"</strong>.
                    <form method='POST'>
                        <div class='form-group mt-3'>
                            <label for='confirmacion'>Escribe \"ELIMINAR\" para confirmar:</label>
                            <input type='text' class='form-control' id='confirmacion' name='confirmacion' required>
                        </div>
                        <div class='modal-footer'>
                            <button type='submit' class='btn btn-danger'>Confirmar Eliminación</button>
                            <a href='celulares_list.php' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar el modal automáticamente
        var myModal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
        myModal.show();
    </script>
    ";
}

// Finalizamos el buffer de salida
ob_end_flush();
?>

<?php require_once 'footer.php'; ?>
