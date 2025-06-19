<?php
ob_start();
// Iniciar buffer de salida
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';
require_once 'header.php';

// Cargar usuarios para el desplegable
$stmt = $pdo->query("SELECT id, primer_nombre, apellido_paterno FROM usuarios ORDER BY primer_nombre ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_POST['id_usuario'];
    $tipo_equipo = $_POST['tipo_equipo'];
    $descripcion = $_POST['descripcion']; // 🆕 Campo añadido
    $estado = $_POST['estado'];
    $fecha_subida = date('Y-m-d');

    // Validación de archivo
    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] != 0) {
        $error = "Error al subir el archivo.";
    } else {
        $archivo = $_FILES['archivo'];
        $nombre_archivo = basename($archivo['name']);
        $ruta_final = uniqid() . '_' . $nombre_archivo;
        $ruta_destino = "../uploads/responsivas/" . $ruta_final;

        // Validar extensión PDF
        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        if ($extension !== 'pdf') {
            $error = "Solo se permiten archivos PDF.";
        } elseif (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            // Guardar en base de datos
            $sql = "INSERT INTO responsivas (id_usuario, tipo_equipo, descripcion, ruta_archivo, nombre_archivo, estado, fecha_subida)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id_usuario, $tipo_equipo, $descripcion, $ruta_final, $nombre_archivo, $estado, $fecha_subida])) {
                $_SESSION['success'] = "Responsiva subida correctamente.";
                header("Location: responsivas_list.php");
                exit;
            } else {
                $error = "Error al guardar en la base de datos.";
            }
        } else {
            $error = "Error al mover el archivo.";
        }
    }
}
ob_end_flush(); // Enviar buffer de salida
?>

<!-- 🟢 HTML Formulario -->
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h4 class="mb-4">Subir Responsiva</h4>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Usuario</label>
                    <select name="id_usuario" class="form-control" required>
                        <option value="">Selecciona un usuario</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= $u['id'] ?>">
                                <?= htmlspecialchars($u['primer_nombre'] . ' ' . $u['apellido_paterno']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tipo de equipo</label>
                    <input type="text" name="tipo_equipo" class="form-control" placeholder="Ej. Laptop, Celular, Audífonos" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="Ej. Marca, modelo, características..." required>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control" required>
                        <option value="Vigente">Vigente</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Archivo PDF</label>
                    <input type="file" name="archivo" accept="application/pdf" class="form-control-file" required>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload mr-1"></i> Subir Responsiva
                </button>
                <a href="responsivas_list.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
