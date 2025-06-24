<?php
// funciones_historial.php
// Funciones para manejar el historial de asignaciones de equipos

/**
 * Registrar una nueva asignación en el historial
 */
function registrar_asignacion($pdo, $equipo_id, $usuario_id, $tipo_equipo, $departamento = null, $notas = null, $usuario_que_asigno = null) {
    try {
        $stmt = $pdo->prepare("INSERT INTO historial_asignaciones 
            (equipo_id, usuario_id, tipo_equipo, fecha_asignacion, departamento, notas, usuario_que_asigno) 
            VALUES (?, ?, ?, NOW(), ?, ?, ?)");
        
        $stmt->execute([
            $equipo_id,
            $usuario_id,
            $tipo_equipo,
            $departamento,
            $notas,
            $usuario_que_asigno
        ]);
        
        return true;
    } catch (PDOException $e) {
        error_log("Error al registrar asignación: " . $e->getMessage());
        return false;
    }
}

/**
 * Registrar la desasignación de un equipo
 */
function registrar_desasignacion($pdo, $equipo_id, $tipo_equipo, $notas = null, $usuario_que_desasigno = null) {
    try {
        // Buscar la asignación activa más reciente
        $stmt = $pdo->prepare("SELECT id FROM historial_asignaciones 
            WHERE equipo_id = ? AND tipo_equipo = ? AND fecha_desasignacion IS NULL 
            ORDER BY fecha_asignacion DESC LIMIT 1");
        $stmt->execute([$equipo_id, $tipo_equipo]);
        $historial_id = $stmt->fetchColumn();
        
        if ($historial_id) {
            // Actualizar la fecha de desasignación
            $stmt = $pdo->prepare("UPDATE historial_asignaciones 
                SET fecha_desasignacion = NOW(), notas = CONCAT(IFNULL(notas, ''), ' | Desasignado por: ', ?) 
                WHERE id = ?");
            $stmt->execute([$usuario_que_desasigno, $historial_id]);
            return true;
        }
        
        return false;
    } catch (PDOException $e) {
        error_log("Error al registrar desasignación: " . $e->getMessage());
        return false;
    }
}

/**
 * Obtener el historial completo de un equipo
 */
function obtener_historial_equipo($pdo, $equipo_id, $tipo_equipo) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                ha.*,
                u.primer_nombre, u.apellido_paterno,
                s.nombre AS super_nombre, s.apellido AS super_apellido,
                sa.nombre AS asignador_nombre, sa.apellido AS asignador_apellido
            FROM historial_asignaciones ha
            LEFT JOIN usuarios u ON ha.usuario_id = u.id
            LEFT JOIN superusuarios s ON ha.usuario_id = s.id
            LEFT JOIN superusuarios sa ON ha.usuario_que_asigno = sa.id
            WHERE ha.equipo_id = ? AND ha.tipo_equipo = ?
            ORDER BY ha.fecha_asignacion DESC
        ");
        
        $stmt->execute([$equipo_id, $tipo_equipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener historial: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtener el historial de asignaciones de un usuario
 */
function obtener_historial_usuario($pdo, $usuario_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                ha.*,
                CASE 
                    WHEN ha.tipo_equipo = 'computo' THEN ec.nombre_pc
                    WHEN ha.tipo_equipo = 'celular' THEN cel.marca
                    WHEN ha.tipo_equipo = 'tablet' THEN (SELECT marca FROM equipo_tablet WHERE id = ha.equipo_id LIMIT 1)
                    WHEN ha.tipo_equipo = 'tv' THEN (SELECT marca FROM equipo_tv WHERE id = ha.equipo_id LIMIT 1)
                    WHEN ha.tipo_equipo = 'periferico' THEN (SELECT marca FROM equipo_periferico WHERE id = ha.equipo_id LIMIT 1)
                    WHEN ha.tipo_equipo = 'impresora' THEN (SELECT marca FROM equipo_impresora WHERE id = ha.equipo_id LIMIT 1)
                END AS equipo_nombre,
                CASE 
                    WHEN ha.tipo_equipo = 'computo' THEN ec.serial_pc
                    WHEN ha.tipo_equipo = 'celular' THEN cel.n_serie
                    WHEN ha.tipo_equipo = 'tablet' THEN (SELECT n_serie FROM equipo_tablet WHERE id = ha.equipo_id LIMIT 1)
                    WHEN ha.tipo_equipo = 'tv' THEN (SELECT n_serie FROM equipo_tv WHERE id = ha.equipo_id LIMIT 1)
                    WHEN ha.tipo_equipo = 'periferico' THEN (SELECT n_serie FROM equipo_periferico WHERE id = ha.equipo_id LIMIT 1)
                    WHEN ha.tipo_equipo = 'impresora' THEN (SELECT n_serie FROM equipo_impresora WHERE id = ha.equipo_id LIMIT 1)
                END AS equipo_serie
            FROM historial_asignaciones ha
            LEFT JOIN equipo_computo ec ON ha.equipo_id = ec.id AND ha.tipo_equipo = 'computo'
            LEFT JOIN equipo_celular cel ON ha.equipo_id = cel.id AND ha.tipo_equipo = 'celular'
            WHERE ha.usuario_id = ?
            ORDER BY ha.fecha_asignacion DESC
        ");
        
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener historial de usuario: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtener estadísticas de asignaciones
 */
function obtener_estadisticas_asignaciones($pdo) {
    try {
        $stats = [];
        
        // Total de asignaciones activas por tipo
        $stmt = $pdo->prepare("
            SELECT tipo_equipo, COUNT(*) as total
            FROM historial_asignaciones 
            WHERE fecha_desasignacion IS NULL
            GROUP BY tipo_equipo
        ");
        $stmt->execute();
        $stats['activas_por_tipo'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Asignaciones del mes actual
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total
            FROM historial_asignaciones 
            WHERE MONTH(fecha_asignacion) = MONTH(NOW()) 
            AND YEAR(fecha_asignacion) = YEAR(NOW())
        ");
        $stmt->execute();
        $stats['asignaciones_mes'] = $stmt->fetchColumn();
        
        // Desasignaciones del mes actual
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total
            FROM historial_asignaciones 
            WHERE MONTH(fecha_desasignacion) = MONTH(NOW()) 
            AND YEAR(fecha_desasignacion) = YEAR(NOW())
            AND fecha_desasignacion IS NOT NULL
        ");
        $stmt->execute();
        $stats['desasignaciones_mes'] = $stmt->fetchColumn();
        
        return $stats;
    } catch (PDOException $e) {
        error_log("Error al obtener estadísticas: " . $e->getMessage());
        return [];
    }
}

/**
 * Formatear fecha para mostrar
 */
function formatear_fecha_historial($fecha) {
    if (!$fecha || $fecha == "0000-00-00 00:00:00") {
        return "Sin fecha";
    }
    
    $timestamp = strtotime($fecha);
    $meses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    
    return date('d', $timestamp) . ' ' . $meses[date('n', $timestamp) - 1] . ' ' . date('Y', $timestamp) . 
           ' a las ' . date('H:i', $timestamp);
}

/**
 * Obtener nombre del usuario asignado
 */
function obtener_nombre_usuario_asignado($row) {
    if (!empty($row['primer_nombre'])) {
        return htmlspecialchars($row['primer_nombre'] . ' ' . $row['apellido_paterno']);
    } elseif (!empty($row['super_nombre'])) {
        return htmlspecialchars($row['super_nombre'] . ' ' . $row['super_apellido']);
    } else {
        return 'Sin asignar';
    }
}
?> 