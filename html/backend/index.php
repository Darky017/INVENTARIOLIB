<?php
session_start();

require_once '../config.php'; // Asegúrate de que la conexión a la BD está bien

// Verificar si el usuario está autenticado
if (!isset($_SESSION['superusuario'])) {
    header("Location: auth-sign-in.php");
    exit();
}

// Obtener el nombre completo del usuario autenticado
$nombreCompleto = $_SESSION['superusuario']['nombre'] . " " . $_SESSION['superusuario']['apellido'];

// Obtener fecha de creación
$fechaCreacion = $_SESSION['superusuario']['created_at'] ?? null;
if ($fechaCreacion && strtotime($fechaCreacion) > 0) {
    $fechaFormateada = date("d M, Y", strtotime($fechaCreacion));
} else {
    $fechaFormateada = "Fecha desconocida";
}

// Función para contar registros en una tabla
function contarRegistros($pdo, $tabla) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $tabla");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0; // Si hay un error, devuelve 0 para evitar fallos
    }
}

// Contar registros de cada tabla
$count_asesores = contarRegistros($pdo, "asesores");
$count_usuarios = contarRegistros($pdo, "usuarios");
$count_equipos = contarRegistros($pdo, "equipos_computo");
$count_cargadores = contarRegistros($pdo, "cargadores");
$count_celulares = contarRegistros($pdo, "celulares_corporativos");
$count_sims = contarRegistros($pdo, "sims_asignadas");
$count_garantias = contarRegistros($pdo, "garantias");

// Cargar el contenido de index.html
$indexContent = file_get_contents('index.html');

// Reemplazar los datos del usuario en el HTML
$indexContent = str_replace('{{usuario}}', htmlspecialchars($nombreCompleto), $indexContent);
$indexContent = str_replace('{{fecha_creacion}}', "Desde " . htmlspecialchars($fechaFormateada), $indexContent);

// Reemplazar contadores en el HTML
$indexContent = str_replace('{{count_asesores}}', $count_asesores, $indexContent);
$indexContent = str_replace('{{count_usuarios}}', $count_usuarios, $indexContent);
$indexContent = str_replace('{{count_equipos}}', $count_equipos, $indexContent);
$indexContent = str_replace('{{count_cargadores}}', $count_cargadores, $indexContent);
$indexContent = str_replace('{{count_celulares}}', $count_celulares, $indexContent);
$indexContent = str_replace('{{count_sims}}', $count_sims, $indexContent);
$indexContent = str_replace('{{count_garantias}}', $count_garantias, $indexContent);

// Agregar botón de logout
$logoutButton = '<a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>';
$indexContent = str_replace('{{logout}}', $logoutButton, $indexContent);

// Mostrar el HTML modificado
echo $indexContent;
?>
