<?php
require_once '../config.php';

try {
    // Crear tabla de historial de asignaciones
    $sql = "CREATE TABLE IF NOT EXISTS historial_asignaciones (
        id INT AUTO_INCREMENT PRIMARY KEY,
        equipo_id INT NOT NULL,
        usuario_id INT,
        tipo_equipo ENUM('computo', 'celular', 'tablet', 'tv', 'periferico', 'impresora') NOT NULL,
        fecha_asignacion DATETIME NOT NULL,
        fecha_desasignacion DATETIME NULL,
        departamento VARCHAR(255),
        notas TEXT,
        usuario_que_asigno INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_equipo_id (equipo_id),
        INDEX idx_usuario_id (usuario_id),
        INDEX idx_fecha_asignacion (fecha_asignacion),
        INDEX idx_tipo_equipo (tipo_equipo)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Tabla historial_asignaciones creada exitosamente.<br>";
    
    // Crear índices adicionales para mejorar el rendimiento
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_equipo_fecha ON historial_asignaciones(equipo_id, fecha_asignacion)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_usuario_fecha ON historial_asignaciones(usuario_id, fecha_asignacion)");
    
    echo "✅ Índices adicionales creados exitosamente.<br>";
    
    echo "<br>🎉 La tabla de historial de asignaciones está lista para usar.";
    
} catch (PDOException $e) {
    echo "❌ Error al crear la tabla: " . $e->getMessage();
}
?> 