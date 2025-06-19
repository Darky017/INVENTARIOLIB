<?php
session_start();
require_once '../config.php';
require_once 'header.php';

$autorizadores = $pdo->query("SELECT id, nombre, segundo_nombre, apellido_paterno, apellido_materno, cargo FROM autorizadores ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$usuarios = $pdo->query("SELECT id, primer_nombre, segundo_nombre, apellido_paterno, apellido_materno, correo_corporativo FROM usuarios ORDER BY primer_nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Estilos personalizados + Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .container { max-width: 650px; margin-top: 30px; }
    .card { border-radius: 10px; }
</style>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5>Generador de Responsivas</h5>
        </div>
        <div class="card-body">
            <form action="responsiva_generar.php" method="POST">
                <div class="form-group">
                    <label for="select_usuario"><strong>Seleccionar usuario:</strong></label>
                    <select name="select_usuario" id="select_usuario" class="form-control" required>
                        <option value="">-- Seleccione un usuario --</option>
                        <?php foreach ($usuarios as $u): 
                            $nombre_completo = trim($u['primer_nombre'] . ' ' . $u['segundo_nombre'] . ' ' . $u['apellido_paterno'] . ' ' . $u['apellido_materno']);
                        ?>
                            <option value="<?= htmlspecialchars($nombre_completo) ?>" data-correo="<?= htmlspecialchars($u['correo_corporativo']) ?>">
                                <?= htmlspecialchars($nombre_completo) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" name="nombre_usuario" id="nombre_usuario" required>
                <input type="hidden" name="correo_usuario" id="correo_usuario">

                <div class="form-group">
                    <label for="departamento"><strong>Departamento del usuario:</strong></label>
                    <input type="text" name="departamento" id="departamento" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="cargo_usuario"><strong>Cargo del usuario:</strong></label>
                    <input type="text" name="cargo_usuario" id="cargo_usuario" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="telefono_usuario"><strong>Teléfono del usuario:</strong></label>
                    <input type="text" name="telefono_usuario" id="telefono_usuario" class="form-control">
                </div>

                <hr>
                <h6 class="text-primary">Datos del Equipo</h6>

                <div id="equipos-container">
                    <div class="equipo-item row mb-2">
                        <div class="col-md-4">
                            <input type="text" name="descripcion[]" class="form-control" placeholder="Descripción" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="marca_modelo[]" class="form-control" placeholder="Marca / Modelo" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="numero_serie[]" class="form-control" placeholder="Número de Serie" required>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-3" onclick="agregarFilaEquipo()">+ Agregar otra fila</button>

                <div class="form-group">
                    <label for="id_autorizador"><strong>Autorizado por:</strong></label>
                    <select name="id_autorizador" id="id_autorizador" class="form-control" required>
                        <option value="">-- Seleccione autorizador --</option>
                        <?php foreach ($autorizadores as $a): ?>
                            <option value="<?= $a['id'] ?>">
                                <?= htmlspecialchars($a['nombre'].' '.$a['segundo_nombre'].' '.$a['apellido_paterno'].' '.$a['apellido_materno'].' - '.$a['cargo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Generar PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts: Select2 + lógica JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#select_usuario').select2({
        placeholder: "Buscar usuario...",
        width: '100%'
    });

    $('#select_usuario').on('change', function () {
        const selected = this.options[this.selectedIndex];
        const nombre = selected.value;
        const correo = selected.getAttribute('data-correo');

        document.getElementById('nombre_usuario').value = nombre;
        document.getElementById('correo_usuario').value = correo;
    });
});

function agregarFilaEquipo() {
    const container = document.getElementById('equipos-container');
    const fila = document.createElement('div');
    fila.classList.add('equipo-item', 'row', 'mb-2');
    fila.innerHTML = `
        <div class="col-md-4">
            <input type="text" name="descripcion[]" class="form-control" placeholder="Descripción" required>
        </div>
        <div class="col-md-4">
            <input type="text" name="marca_modelo[]" class="form-control" placeholder="Marca / Modelo" required>
        </div>
        <div class="col-md-4">
            <input type="text" name="numero_serie[]" class="form-control" placeholder="Número de Serie" required>
        </div>
    `;
    container.appendChild(fila);
}
</script>

<?php require_once 'footer.php'; ?>
