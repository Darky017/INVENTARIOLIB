<?php
// config.php

// Parámetros de conexión
$host   = 'localhost:3307';
$dbname = 'inventario';  // Asegúrate de que este sea el nombre de tu base de datos
$user   = 'root';
$pass   = ''; // Contraseña vacía para el usuario root

// Función para verificar la conexión
function test_connection() {
    global $host, $user, $pass, $dbname;
    
    try {
        echo "Intentando conectar a MySQL...\n";
        // Intentar conexión sin base de datos primero
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Conexión a MySQL exitosa.\n\n";

        // Intentar listar las bases de datos disponibles
        echo "Listando bases de datos disponibles:\n";
        $stmt = $pdo->query("SHOW DATABASES");
        $databases = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $databases[] = $row['Database'];
            echo "- " . $row['Database'] . "\n";
        }
        echo "\n";

        // Verificar permisos del usuario
        echo "Verificando permisos del usuario actual:\n";
        $stmt = $pdo->query("SHOW GRANTS FOR CURRENT_USER");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
        echo "\n";

        // Verificar si la base de datos existe
        if (!in_array($dbname, $databases)) {
            echo "Error: La base de datos '$dbname' no existe en la lista de bases de datos disponibles.\n";
            echo "Puedes crearla con el siguiente comando SQL:\n";
            echo "CREATE DATABASE $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
            return false;
        }
        
        return true;
    } catch (PDOException $e) {
        echo "Error de conexión detallado:\n";
        echo "Código de error: " . $e->getCode() . "\n";
        echo "Mensaje: " . $e->getMessage() . "\n";
        echo "Host: $host\n";
        echo "Usuario: $user\n";
        echo "Base de datos: $dbname\n";
        
        // Verificar si el servidor MySQL está corriendo
        if ($e->getCode() == 2002) {
            echo "\nEl servidor MySQL parece no estar corriendo. Por favor, verifica que el servicio esté activo.\n";
        }
        
        return false;
    }
}

try {
    // Probar la conexión primero
    if (!test_connection()) {
        die("No se pudo establecer la conexión. Por favor, verifica los errores anteriores.");
    }
    
    // Si la prueba es exitosa, crear la conexión con la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "\nConexión exitosa a la base de datos '$dbname'.\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// --- FUNCIÓN DE AUDITORÍA ---
function registrar_auditoria($pdo, $usuario_id, $accion, $descripcion, $tabla, $id_registro) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO auditoria (usuario_id, accion, descripcion, tabla_afectada, id_registro_afectado, ip_usuario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $accion, $descripcion, $tabla, $id_registro, $ip]);
}

$usuario_id = $_SESSION['superusuario']['id'] ?? null;
?>