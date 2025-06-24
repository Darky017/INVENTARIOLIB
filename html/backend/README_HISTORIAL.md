# Sistema de Historial de Asignaciones de Equipos

## Descripción
Este sistema permite rastrear todo el historial de asignaciones de equipos a usuarios, manteniendo un registro completo de cuándo se asignó cada equipo, a quién, cuándo se desasignó, y quién realizó cada acción.

## Características

### ✅ Funcionalidades Implementadas
- **Registro automático de asignaciones**: Cada vez que se asigna un equipo a un usuario, se registra en el historial
- **Registro de desasignaciones**: Cuando se cambia la asignación de un equipo, se marca la desasignación anterior
- **Historial completo**: Vista general de todas las asignaciones con filtros avanzados
- **Historial por equipo**: Vista específica del historial de cada equipo individual
- **Migración de datos**: Script para migrar asignaciones existentes al nuevo sistema
- **Auditoría**: Registro de quién realizó cada asignación/desasignación

### 📊 Tipos de Equipos Soportados
- Computadoras (`computo`)
- Celulares (`celular`)
- Tablets (`tablet`)
- TVs (`tv`)
- Periféricos (`periferico`)
- Impresoras (`impresora`)

## Instalación y Configuración

### 1. Crear la Tabla de Historial
Ejecuta el archivo `crear_tabla_historial.php` para crear la tabla necesaria:
```
http://tu-sitio.com/html/backend/crear_tabla_historial.php
```

### 2. Migrar Datos Existentes
Ejecuta el archivo `migrar_historial_existente.php` para migrar las asignaciones existentes:
```
http://tu-sitio.com/html/backend/migrar_historial_existente.php
```

### 3. Verificar la Integración
Los archivos ya han sido modificados para incluir el registro automático del historial:
- `equipos_add.php` - Registra asignaciones al crear equipos
- `equipos_edit.php` - Registra cambios de asignación al editar equipos

## Uso del Sistema

### Vista General del Historial
Accede a través del menú principal: **Historial**
- URL: `historial_asignaciones.php`
- Filtros disponibles:
  - Tipo de equipo
  - Usuario específico
  - Rango de fechas
  - ID del equipo
- Paginación de 20 registros por página
- Estadísticas en tiempo real

### Historial de un Equipo Específico
Desde la lista de equipos, haz clic en **Ver historial** en el menú de acciones
- URL: `historial_equipo.php?id=X&tipo=computo`
- Muestra información completa del equipo
- Timeline de todas las asignaciones
- Duración de cada asignación
- Notas y detalles de cada cambio

### Acceso desde Menús
- **Menú principal**: Enlace "Historial" en la barra de navegación
- **Lista de equipos**: Menú de acciones → "Ver historial"
- **Lista de celulares**: Menú de acciones → "Ver historial" (cuando se implemente)

## Estructura de la Base de Datos

### Tabla: `historial_asignaciones`
```sql
CREATE TABLE historial_asignaciones (
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
);
```

## Funciones Disponibles

### `funciones_historial.php`
- `registrar_asignacion()` - Registra una nueva asignación
- `registrar_desasignacion()` - Marca una asignación como terminada
- `obtener_historial_equipo()` - Obtiene historial de un equipo específico
- `obtener_historial_usuario()` - Obtiene historial de un usuario específico
- `obtener_estadisticas_asignaciones()` - Obtiene estadísticas generales
- `formatear_fecha_historial()` - Formatea fechas para mostrar
- `obtener_nombre_usuario_asignado()` - Obtiene nombre del usuario asignado

## Archivos del Sistema

### Archivos Principales
- `historial_asignaciones.php` - Vista general del historial
- `historial_equipo.php` - Vista específica de un equipo
- `funciones_historial.php` - Funciones de utilidad
- `crear_tabla_historial.php` - Script de instalación
- `migrar_historial_existente.php` - Script de migración

### Archivos Modificados
- `equipos_add.php` - Agregado registro de historial
- `equipos_edit.php` - Agregado registro de cambios
- `header.php` - Agregado enlace al historial

## Próximos Pasos

### Para Implementar en Otros Tipos de Equipos
1. Modificar los archivos de agregar/editar de cada tipo
2. Agregar enlaces al historial en las listas correspondientes
3. Actualizar las funciones para manejar las columnas específicas de cada tabla

### Mejoras Futuras
- Exportar historial a PDF/Excel
- Notificaciones de cambios de asignación
- Dashboard con estadísticas avanzadas
- Reportes de tiempo de uso por usuario
- Integración con sistema de tickets

## Soporte

Para problemas o preguntas sobre el sistema de historial:
1. Verificar que la tabla `historial_asignaciones` existe
2. Confirmar que los datos se migraron correctamente
3. Revisar los logs de error de PHP
4. Verificar que las funciones están incluidas en los archivos correspondientes

## Notas Importantes

- El sistema registra automáticamente todas las asignaciones nuevas
- Los datos existentes deben migrarse manualmente usando el script proporcionado
- El historial es inmutable una vez creado (no se puede editar)
- Todas las acciones quedan registradas con timestamp y usuario que las realizó 