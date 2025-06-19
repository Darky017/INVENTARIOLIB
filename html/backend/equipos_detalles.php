<?php
session_start();

require_once '../config.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    header("Location: equipos_list.php");
    exit();
}

$id = $_GET['id'];

// Función para obtener datos de cada tabla con la clave correcta
function obtenerDatos($pdo, $tabla, $id, $usar_equipo_id = true) {
    $columna = $usar_equipo_id ? 'equipo_id' : 'id'; // Usar 'id' solo para equipo_computo
    $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE $columna = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}

// Obtener datos del equipo (filtro por 'id')
$equipo = obtenerDatos($pdo, 'equipo_computo', $id, false);

// Obtener datos de las demás secciones (filtro por 'equipo_id')
$software = obtenerDatos($pdo, 'software', $id);
$bitlocker = obtenerDatos($pdo, 'bitlocker', $id);
$caracteristicas = obtenerDatos($pdo, 'caracteristicas_tecnicas', $id);
$garantia = obtenerDatos($pdo, 'garantia', $id);
$cargador = obtenerDatos($pdo, 'cargador', $id);
$sistema = obtenerDatos($pdo, 'sistema', $id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Equipo</title>
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
            <h4>Gestión del Equipo: <?php echo htmlspecialchars($equipo['nombre_pc'] ?? 'Equipo no encontrado'); ?></h4>
        </div>
        <div class="card-body">
            <!-- Pestañas -->
            <ul class="nav nav-tabs" id="equiposTab" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#software">Software</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bitlocker">BitLocker</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#caracteristicas">Características</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#garantia">Garantía</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cargador">Cargador</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sistema">Sistema</a></li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content mt-3">
                <!-- Software -->
                <div class="tab-pane fade show active" id="software">
                    <?php include 'software_view.php'; ?>
                </div>

                <!-- BitLocker -->
                <div class="tab-pane fade" id="bitlocker">
                    <?php include 'bitlocker_view.php'; ?>
                </div>

                <!-- Características Técnicas -->
                <div class="tab-pane fade" id="caracteristicas">
                    <?php include 'caracteristicas_view.php'; ?>
                </div>

                <!-- Garantía -->
                <div class="tab-pane fade" id="garantia">
                    <?php include 'garantia_view.php'; ?>
                </div>

                <!-- Cargador -->
                <div class="tab-pane fade" id="cargador">
                    <?php include 'cargador_view.php'; ?>
                </div>

                <!-- Sistema -->
                <div class="tab-pane fade" id="sistema">
                    <?php include 'sistema_view.php'; ?>
                </div>
            </div>

            <div class="text-center my-4" id="qr">
                <h5>Código QR del equipo</h5>
                <img src="generar_qr.php?id=<?= $equipo['id']; ?>" alt="QR del equipo" width="200" height="200" style="border: 2px solid #00813a; background: #fff;">
                <br>
                <a href="generar_qr.php?id=<?= $equipo['id']; ?>" download="qr_equipo_<?= $equipo['id']; ?>.png" class="btn btn-success mt-2">
                    <i class="fas fa-download"></i> Descargar QR
                </a>
            </div>

            <div class="text-center mt-4">
                <a href="equipos_list.php" class="btn btn-secondary">Volver a la Lista</a>
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
