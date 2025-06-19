<?php
session_start();
require_once '../config.php';

// Verificación del parámetro 'id' en la URL
if (!isset($_GET['id'])) {
    header("Location: celulares_list.php");
    exit();
}

$id = $_GET['id'];

// Función para obtener datos por ID de celular
function obtenerDatos($pdo, $tabla, $id, $columna = 'id_celular') {
    $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE $columna = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}

// Datos principales del celular
$celular = obtenerDatos($pdo, 'equipo_celular', $id, 'id');
$caracteristicas = obtenerDatos($pdo, 'caracteristicas_celular', $id);
$sim = obtenerDatos($pdo, 'equipo_cel_sim', $id);
$esim = obtenerDatos($pdo, 'esim', $id);
$cargador = obtenerDatos($pdo, 'cargador_celular', $id);

require_once 'header.php'; // Asegúrate de que esto esté después de la lógica PHP que modifica las cabeceras
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Celular</title>
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <style>
        .container { max-width: 900px; }
        .card { margin-top: 20px; }
        .nav-tabs { border-bottom: 2px solid #007bff; }
        .nav-tabs .nav-link { color: #007bff; font-weight: bold; }
        .nav-tabs .nav-link.active { background-color: #007bff; color: white; border-radius: 5px; }
        .tab-content { padding: 15px; background-color: white; border: 1px solid #ddd; border-top: none; }
        .no-data { color: gray; font-style: italic; }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white text-center">
            <h4>Gestión del Celular: <?php echo htmlspecialchars($celular['marca'] . ' ' . $celular['modelo'] ?? 'Celular no encontrado'); ?></h4>
        </div>
        <div class="card-body">
            <!-- Pestañas -->
            <ul class="nav nav-tabs" id="celularTab" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#caracteristicas">Características Técnicas</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sim">SIM Física</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#esim">eSIM</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cargador">Cargador</a></li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content mt-3">
                <!-- Características Técnicas -->
                <div class="tab-pane fade show active" id="caracteristicas">
                    <?php include 'caracteristicas_celular_view.php'; ?>
                </div>

                <!-- SIM Física -->
                <div class="tab-pane fade" id="sim">
                    <?php include 'sim_view.php'; ?>
                </div>

                <!-- eSIM -->
                <div class="tab-pane fade" id="esim">
                    <?php include 'esim_view.php'; ?>
                </div>

                <!-- Cargador -->
                <div class="tab-pane fade" id="cargador">
                    <?php include 'cargador_celular_view.php'; ?>
                </div>
            </div>

            <div class="text-center my-4" id="qr">
                <h5>Código QR del celular</h5>
                <img src="generar_qr.php?id=<?= $celular['id']; ?>" alt="QR del celular" width="200" height="200" style="border: 2px solid #00813a; background: #fff;">
                <br>
                <a href="generar_qr.php?id=<?= $celular['id']; ?>" download="qr_celular_<?= $celular['id']; ?>.png" class="btn btn-success mt-2">
                    <i class="fas fa-download"></i> Descargar QR
                </a>
            </div>

            <div class="text-center mt-4">
                <a href="celulares_list.php" class="btn btn-secondary">Volver a la Lista</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            let target = $(e.target).attr("href");
            $(".tab-pane").removeClass("show active");
            $(target).addClass("show active");
        });
    });
</script>

<?php require_once 'footer.php'; ?>
</body>
</html>
