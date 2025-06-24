<?php
require_once '../config.php';
require_once 'funciones_historial.php';

echo "<h2>Migración de Historial de Asignaciones</h2>";

try {
    // 1. Migrar equipos de cómputo
    echo "<h3>Migrando equipos de cómputo...</h3>";
    $stmt = $pdo->query("SELECT id, usuario, asignacion, departamento FROM equipo_computo WHERE usuario IS NOT NULL");
    $equipos_computo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $contador_computo = 0;
    foreach ($equipos_computo as $equipo) {
        if (!empty($equipo['usuario'])) {
            $fecha_asignacion = $equipo['asignacion'] ?: date('Y-m-d H:i:s');
            
            // Verificar si ya existe en el historial
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM historial_asignaciones WHERE equipo_id = ? AND tipo_equipo = 'computo'");
            $stmt_check->execute([$equipo['id']]);
            
            if ($stmt_check->fetchColumn() == 0) {
                registrar_asignacion(
                    $pdo,
                    $equipo['id'],
                    $equipo['usuario'],
                    'computo',
                    $equipo['departamento'],
                    'Migración de datos existentes',
                    null
                );
                $contador_computo++;
            }
        }
    }
    echo "✅ Migrados $contador_computo equipos de cómputo<br>";

    // 2. Migrar celulares
    echo "<h3>Migrando celulares...</h3>";
    $stmt = $pdo->query("SELECT id, id_usuario, fecha_asignacion FROM equipo_celular WHERE id_usuario IS NOT NULL");
    $celulares = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $contador_celulares = 0;
    foreach ($celulares as $celular) {
        if (!empty($celular['id_usuario'])) {
            $fecha_asignacion = $celular['fecha_asignacion'] ?: date('Y-m-d H:i:s');
            
            // Verificar si ya existe en el historial
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM historial_asignaciones WHERE equipo_id = ? AND tipo_equipo = 'celular'");
            $stmt_check->execute([$celular['id']]);
            
            if ($stmt_check->fetchColumn() == 0) {
                registrar_asignacion(
                    $pdo,
                    $celular['id'],
                    $celular['id_usuario'],
                    'celular',
                    null,
                    'Migración de datos existentes',
                    null
                );
                $contador_celulares++;
            }
        }
    }
    echo "✅ Migrados $contador_celulares celulares<br>";

    // 3. Verificar si existen otras tablas y migrarlas
    $tablas_equipos = [
        'equipo_tablet' => 'tablet',
        'equipo_tv' => 'tv',
        'equipo_periferico' => 'periferico',
        'equipo_impresora' => 'impresora'
    ];

    foreach ($tablas_equipos as $tabla => $tipo) {
        // Verificar si la tabla existe
        $stmt_check_table = $pdo->query("SHOW TABLES LIKE '$tabla'");
        if ($stmt_check_table->rowCount() > 0) {
            echo "<h3>Migrando $tipo...</h3>";
            
            // Obtener la columna de usuario (puede variar según la tabla)
            $stmt_columns = $pdo->query("SHOW COLUMNS FROM $tabla");
            $columns = $stmt_columns->fetchAll(PDO::FETCH_COLUMN);
            
            $usuario_column = null;
            foreach (['usuario', 'id_usuario', 'user_id'] as $possible_column) {
                if (in_array($possible_column, $columns)) {
                    $usuario_column = $possible_column;
                    break;
                }
            }
            
            if ($usuario_column) {
                $stmt = $pdo->query("SELECT id, $usuario_column FROM $tabla WHERE $usuario_column IS NOT NULL");
                $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $contador = 0;
                foreach ($equipos as $equipo) {
                    if (!empty($equipo[$usuario_column])) {
                        // Verificar si ya existe en el historial
                        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM historial_asignaciones WHERE equipo_id = ? AND tipo_equipo = ?");
                        $stmt_check->execute([$equipo['id'], $tipo]);
                        
                        if ($stmt_check->fetchColumn() == 0) {
                            registrar_asignacion(
                                $pdo,
                                $equipo['id'],
                                $equipo[$usuario_column],
                                $tipo,
                                null,
                                'Migración de datos existentes',
                                null
                            );
                            $contador++;
                        }
                    }
                }
                echo "✅ Migrados $contador $tipo<br>";
            }
        }
    }

    // 4. Mostrar estadísticas finales
    echo "<h3>Estadísticas finales:</h3>";
    $stmt = $pdo->query("SELECT COUNT(*) FROM historial_asignaciones");
    $total_registros = $stmt->fetchColumn();
    echo "Total de registros en el historial: $total_registros<br>";
    
    $stmt = $pdo->query("SELECT tipo_equipo, COUNT(*) as total FROM historial_asignaciones GROUP BY tipo_equipo");
    $estadisticas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<ul>";
    foreach ($estadisticas as $stat) {
        echo "<li>{$stat['tipo_equipo']}: {$stat['total']} registros</li>";
    }
    echo "</ul>";
    
    echo "<br><strong>✅ Migración completada exitosamente!</strong>";
    echo "<br><a href='historial_asignaciones.php' class='btn btn-primary'>Ver Historial</a>";

} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error durante la migración: " . $e->getMessage() . "</div>";
}
?> 