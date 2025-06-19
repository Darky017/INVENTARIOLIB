<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: celulares_list.php");
    exit();
}

$id = $_GET['id'];

// Obtener el celular
$stmtCel = $pdo->prepare("SELECT * FROM equipo_celular WHERE id = :id");
$stmtCel->execute(['id' => $id]);
$celular = $stmtCel->fetch(PDO::FETCH_ASSOC);

// Obtener los SIMs asociados al celular
$stmtSim = $pdo->prepare("SELECT * FROM equipo_cel_sim WHERE id_celular = :id");
$stmtSim->execute(['id' => $id]);
$sims = $stmtSim->fetchAll(PDO::FETCH_ASSOC);

$simCount = count($sims); // Contar cuántos SIMs hay asociados

// Determinar el SIM seleccionado
$simId = isset($_GET['sim']) ? (int)$_GET['sim'] : 1;

// Función para formatear la fecha con el mes en español
function formatFecha($fecha) {
    // Crear un objeto DateTime a partir de la fecha
    $fechaObj = new DateTime($fecha);
    
    // Configurar el locale en español para que los meses estén en español
    setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES');
    
    // Formato con mes en español
    return strftime('%d de %B de %Y', $fechaObj->getTimestamp());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver SIM</title>
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .container { max-width: 800px; }
        .card { margin-top: 30px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <h5 class="text-primary">SIMs Asociados</h5>

            <?php if ($simCount == 0): ?>
                <!-- Si no hay SIMs asociados -->
                <p>No hay SIMs asociados a este celular.</p>
                <a href="sim_add.php?id=<?= $id ?>&sim=1" class="btn btn-primary">Registrar SIM 1</a>

            <?php elseif ($simCount == 1): ?>
                <!-- Si hay 1 SIM asociado -->
                <h5 class="text-success">SIM 1</h5>
                <p><strong>Número:</strong> <?= htmlspecialchars($sims[0]['numero']) ?></p>
                <p><strong>Número de Serie:</strong> <?= htmlspecialchars($sims[0]['n_serie']) ?></p>
                <p><strong>Compañía:</strong> <?= htmlspecialchars($sims[0]['compania']) ?></p>
                <p><strong>Plan:</strong> <?= htmlspecialchars($sims[0]['es_plan']) ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($sims[0]['estado']) ?></p>
                <p><strong>Versión:</strong> <?= htmlspecialchars($sims[0]['version']) ?></p>
                <p><strong>Fecha Asignación:</strong> <?= formatFecha($sims[0]['fecha_asignacion']) ?></p>

                <!-- QR para SIM 1 -->
                <div class="text-center my-4" id="qr">
                    <h5>Código QR de la SIM</h5>
                    <img src="generar_qr.php?id=<?= $sims[0]['id']; ?>" alt="QR de la SIM" width="200" height="200" style="border: 2px solid #00813a; background: #fff;">
                    <br>
                    <a href="generar_qr.php?id=<?= $sims[0]['id']; ?>" download="qr_sim_<?= $sims[0]['id']; ?>.png" class="btn btn-success mt-2">
                        <i class="fas fa-download"></i> Descargar QR
                    </a>
                </div>

                <hr>
                <?php echo '<a href="sim_edit.php?id=' . $id . '&sim_id=' . $sim['id'] . '" class="btn btn-warning btn-sm">Editar</a>';
                ?>
                <a href="sim_desasignar.php?id=<?= $celular['id'] ?>&sim=<?= $sim['id'] ?>" class="btn btn-sm btn-warning" onclick="return confirm('¿Desasignar esta SIM del celular?')">
                    <i class="fas fa-unlink"></i> Desasignar
                </a>
                <a href="sim_add.php?id=<?= $id ?>&sim=2" class="btn btn-primary">Registrar SIM 2</a>

            <?php else: ?>
                <!-- Si hay 2 SIMs asociados -->
                <!-- Dropdown para cambiar entre SIMs -->
                <label for="simSelector">Seleccionar SIM:</label>
                <select id="simSelector" class="form-control" onchange="loadSimDetails(this.value)">
                    <option value="1" <?= ($simId == 1) ? 'selected' : '' ?>>SIM 1</option>
                    <option value="2" <?= ($simId == 2) ? 'selected' : '' ?>>SIM 2</option>
                </select>

                <hr>

                <!-- Aquí solo se actualizará la información de la SIM seleccionada -->
                <div id="simDetails">
                    <?php
                    // Mostrar el SIM seleccionado
                    if (isset($sims[$simId - 1])) {
                        $sim = $sims[$simId - 1];
                        echo "<h5 class='text-success'>SIM $simId</h5>";
                        echo "<p><strong>Número:</strong> " . htmlspecialchars($sim['numero']) . "</p>";
                        echo "<p><strong>Número de Serie:</strong> " . htmlspecialchars($sim['n_serie']) . "</p>";
                        echo "<p><strong>Compañía:</strong> " . htmlspecialchars($sim['compania']) . "</p>";
                        echo "<p><strong>Plan:</strong> " . htmlspecialchars($sim['es_plan']) . "</p>";
                        echo "<p><strong>Estado:</strong> " . htmlspecialchars($sim['estado']) . "</p>";
                        echo "<p><strong>Versión:</strong> " . htmlspecialchars($sim['version']) . "</p>";
                        echo "<p><strong>Fecha Asignación:</strong> " . formatFecha($sim['fecha_asignacion']) . "</p>";
                        // QR para SIM seleccionada
                        echo '<div class="text-center my-4" id="qr">'
                            . '<h5>Código QR de la SIM</h5>'
                            . '<img src="generar_qr.php?id=' . $sim['id'] . '" alt="QR de la SIM" width="200" height="200" style="border: 2px solid #00813a; background: #fff;">'
                            . '<br>'
                            . '<a href="generar_qr.php?id=' . $sim['id'] . '" download="qr_sim_' . $sim['id'] . '.png" class="btn btn-success mt-2">'
                            . '<i class="fas fa-download"></i> Descargar QR'
                            . '</a>'
                            . '</div>';
                        // Botón de editar
                        echo '<a href="sim_edit.php?id=' . $id . '&sim_id=' . $sim['id'] . '" class="btn btn-warning btn-sm">Editar</a>';
                        // Botón de eliminar
                        echo '<a href="sim_desasignar.php?id=' . $celular['id'] . '&sim=' . $sim['id'] . '" class="btn btn-sm btn-warning" onclick="return confirm(\'¿Desasignar esta SIM del celular?\')">'
                            . '<i class="fas fa-unlink"></i> Desasignar'
                            . '</a>';
                    } else {
                        echo "<p>Este SIM no está disponible.</p>";
                    }
                    ?>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function loadSimDetails(simId) {
    var celularId = <?= $id ?>; // Obtener el ID del celular directamente de PHP
    var url = 'sim_view.php?id=' + celularId + '&sim=' + simId;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Actualizar solo el contenido de la información del SIM
            document.getElementById('simDetails').innerHTML = xhr.responseText.split('<hr>')[1];
        }
    };
    xhr.send();
}
</script>

</body>
</html>
