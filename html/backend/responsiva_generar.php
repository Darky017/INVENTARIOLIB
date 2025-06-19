<?php
session_start();
require_once '../config.php';
require_once '../vendor/autoload.php'; // dompdf
use Dompdf\Dompdf;

function limpiar($v) {
    return htmlspecialchars(strtoupper(trim($v)));
}

// Recoger POST
$nombre_usuario = limpiar($_POST['nombre_usuario'] ?? '');
$departamento = limpiar($_POST['departamento'] ?? '');
$cargo_usuario = limpiar($_POST['cargo_usuario'] ?? '');
$correo_usuario = $_POST['correo_usuario'] ?? '';
$telefono_usuario = $_POST['telefono_usuario'] ?? '';
$descripciones = $_POST['descripcion'] ?? [];
$marcas_modelos = $_POST['marca_modelo'] ?? [];
$numeros_serie = $_POST['numero_serie'] ?? [];

$filas_equipo = '';
for ($i = 0; $i < count($descripciones); $i++) {
    $desc = limpiar($descripciones[$i]);
    $marca = limpiar($marcas_modelos[$i]);
    $serie = limpiar($numeros_serie[$i]);
    $filas_equipo .= "<tr><td>$desc</td><td>$marca</td><td>$serie</td></tr>";
}

$id_autorizador = $_POST['id_autorizador'] ?? '';

// Validaciones básicas
if (
    !$nombre_usuario || !$cargo_usuario || !$id_autorizador ||
    empty($_POST['descripcion'][0]) || empty($_POST['marca_modelo'][0]) || empty($_POST['numero_serie'][0])
) {
    die('Faltan campos obligatorios.');
}


// Traer autorizador
$stmt = $pdo->prepare("SELECT nombre, segundo_nombre, apellido_paterno, apellido_materno, cargo FROM autorizadores WHERE id = ?");
$stmt->execute([$id_autorizador]);
$aut = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$aut) die('Autorizador no encontrado');

$nombre_autorizador = strtoupper($aut['nombre'].' '.$aut['segundo_nombre'].' '.$aut['apellido_paterno'].' '.$aut['apellido_materno']);
$cargo_autorizador = $aut['cargo'];

// Fecha en español
setlocale(LC_TIME, 'es_ES.UTF-8');
$fecha_carta = strftime('MÉRIDA, YUCATÁN, A ____ DE ____ DEL AÑO ____.');

// Plantilla HTML
$html = "
<html>
<head>
<style>
body { font-family: Arial, sans-serif; font-size: 14px; }
h2 { text-align: center; text-decoration: underline; margin-bottom: 20px; }
.table-equipo { border-collapse: collapse; width: 100%; margin: 15px 0; }
.table-equipo th, .table-equipo td { border: 1px solid black; padding: 8px; text-align: center; }
.firma { margin-top: 40px; }
</style>
</head>
<body>
<p style='text-align: right;'>$fecha_carta</p>


<h2>CARTA RESPONSIVA</h2>

<p>Por medio de la presente, hago constar que el(la) <b>$nombre_usuario</b>, adscrito(a) al <b>Departamento de $departamento</b> de CASA BASTION S.A. DE C.V., ha recibido en resguardo y para el cumplimiento de sus funciones el siguiente equipo de cómputo y/o telefonía:</p>

<table class='table-equipo'>
<thead>
<tr>
<th>DESCRIPCIÓN</th>
<th>MARCA/MODELO</th>
<th>NÚMERO DE SERIE</th>
</tr>
</thead>
<tbody>
$filas_equipo
</tbody>
</table>
<style>
.table-equipo th, .table-equipo td { border: 1px solid #fff !important; }
</style>

<p><b>Condiciones de Uso y Responsabilidad:</b></p>
<ol>
<li>El equipo deberá ser utilizado exclusivamente para fines laborales y conforme a las políticas de uso de <b>CASA BASTION S.A. DE C.V</b>.</li>
<li>Se compromete a cuidar y mantener el equipo en buen estado, evitando daños por uso indebido o negligencia.</li>
<li>En caso de daño, extravío, robo o mal uso, el(la) responsable deberá notificar de inmediato al <b>Departamento de Tecnología de la Información (TI)</b> y podrá estar sujeto(a) a las políticas internas de reposición o sanción.</li>
<li>El equipo deberá ser devuelto en optimas condiciones al término de la relación laboral o cuando la empresa lo requiera.</li>
</ol>

<p>Manifiesto que he leído y acepto los términos descritos en este documento, haciéndome responsable del resguardo y uso del equipo asignado.</p>
<br>
<br>
<br>
<br>
<br>
<br>
<div class='firma' style='text-align: center;'>

<p>________________________</p>
<p><b>$nombre_usuario</b><br>
<b>$cargo_usuario</b><br>
" . ($correo_usuario ? '<u>' . htmlspecialchars($correo_usuario) . '</u><br>' : '') . "
<b>" . ($telefono_usuario ?: '') . "</b></p>
</div>
<br>
<p style='text-align: center;'><b>AUTORIZADO POR:</b></p>

<div class='firma' style='text-align: center;'>
<p>________________________</p>
<p><b>$nombre_autorizador</b><br>
<b>$cargo_autorizador</b></p>
</div>


</body>
</html>
";

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Forzar descarga
$nombre_archivo = 'Responsiva-' . preg_replace('/[^A-Za-z0-9_\-]/', '-', strtolower($nombre_usuario)) . '.pdf';
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
echo $dompdf->output();
exit;
