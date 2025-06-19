<?php
ob_start(); // Inicia el buffer de salida
session_start();

require_once '../config.php';
require_once 'header.php';

// Verificamos si se ha pasado el ID del equipo
if (!isset($_GET['id'])) {
    die("ID de equipo no proporcionado.");
}

$id = $_GET['id'];

// Verificamos si se ha pasado el parámetro de confirmación en la URL
if (isset($_GET['confirmacion']) && $_GET['confirmacion'] === 'ELIMINAR') {
    // Confirmación recibida, procedemos a eliminar el equipo
    $stmt = $pdo->prepare("DELETE FROM equipo_computo WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // Limpiamos el buffer antes de la redirección
    ob_end_clean();

    // Redirigimos a la lista de equipos después de eliminar
    header('Location: equipos_list.php');
    exit; // Asegura que no se ejecute más código
} else {
    // Si no se pasó confirmación, mostramos el modal para confirmar
    echo "
    <div class='modal fade' id='modalConfirmacion' tabindex='-1' aria-labelledby='modalConfirmacionLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='modalConfirmacionLabel'>Confirmar Eliminación</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <strong>¡Advertencia!</strong> Esta acción no se puede deshacer. ¿Estás seguro de que deseas eliminar este equipo?
                </div>
                <div class='modal-footer'>
                    <a href='equipos_delete.php?id=" . htmlspecialchars($id) . "&confirmacion=ELIMINAR' class='btn btn-danger'>Confirmar Eliminación</a>
                    <a href='equipos_list.php' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</a>
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
