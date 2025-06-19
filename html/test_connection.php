<?php
// test_connection.php
require_once 'config.php';

try {
    // Ejecutamos una consulta simple para comprobar la conexión
    $stmt = $pdo->query("SELECT 1");
    if($stmt->fetchColumn()){
        echo "La conexión a la base de datos fue exitosa.";
    } else {
        echo "La conexión falló.";
    }
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
