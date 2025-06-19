<?php
require_once '../config.php';

if (!isset($_GET['id']) || !isset($_GET['sim'])) {
    echo "Datos incompletos.";
    exit();
}

$id = $_GET['id'];
$simId = $_GET['sim'];

// Obtener los SIMs asociados al celular
$stmtSim = $pdo->prepare("SELECT * FROM equipo_cel_sim WHERE id_celular = :id");
$stmtSim->execute(['id' => $id]);
$sims = $stmtSim->fetchAll(PDO::FETCH_ASSOC);

// Verificar si existe la SIM seleccionada
if (isset($sims[$simId - 1])) {
    $sim = $sims[$simId - 1];
    echo "<h5 class='text-success'>SIM $simId</h5>";
    echo "<p><strong>Número:</strong> " . htmlspecialchars($sim['numero']) . "</p>";
    echo "<p><strong>Compañía:</strong> " . htmlspecialchars($sim['compania']) . "</p>";
    echo "<p><strong>Plan:</strong> " . htmlspecialchars($sim['es_plan']) . "</p>";  <!-- Cambié 'plan' por 'es_plan' -->
    echo "<p><strong>Estado:</strong> " . htmlspecialchars($sim['estado']) . "</p>";
    echo "<p><strong>Fecha Asignación:</strong> " . htmlspecialchars($sim['fecha_asignacion']) . "</p>";
} else {
    echo "<p>Este SIM no está disponible.</p>";
}
?>
