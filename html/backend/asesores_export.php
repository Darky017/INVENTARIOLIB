<?php
ini_set('display_errors', 0);
error_reporting(0);

require_once '../config.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="asesores.xlsx"');
header('Cache-Control: max-age=0');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = [
    'Primer Nombre', 'Segundo Nombre', 'Primer Apellido', 'Segundo Apellido',
    'Correo Personal', 'Num. Personal', 'Zona',
    'Correo Corporativo', 'Contraseña', 'Estado',
    'Fecha Solicitud Baja', 'Fecha Suspensión', 'Fecha Próxima Eliminación',
    'Fecha Respaldo', 'Fecha Eliminación', 'Comentarios'
];

// Escribir encabezados y aplicar estilos
$colIndex = 1;
foreach ($headers as $header) {
    $cell = Coordinate::stringFromColumnIndex($colIndex) . '1';
    $sheet->setCellValue($cell, $header);
    $colIndex++;
}

// Estilo para encabezados
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E0E0E0']
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
];

$colCount = count($headers);
$headerRange = 'A1:' . Coordinate::stringFromColumnIndex($colCount) . '1';
$sheet->getStyle($headerRange)->applyFromArray($headerStyle);

// Obtener datos
$sql = "SELECT * FROM asesores ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$asesores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Escribir filas
$row = 2;
foreach ($asesores as $asesor) {
    $sheet->setCellValue('A' . $row, $asesor['primer_nombre']);
    $sheet->setCellValue('B' . $row, $asesor['segundo_nombre']);
    $sheet->setCellValue('C' . $row, $asesor['primer_apellido']);
    $sheet->setCellValue('D' . $row, $asesor['segundo_apellido']);
    $sheet->setCellValue('E' . $row, $asesor['correo_personal']);
    $sheet->setCellValue('F' . $row, $asesor['n_tel']);
    $sheet->setCellValue('G' . $row, $asesor['zona']);
    $sheet->setCellValue('H' . $row, $asesor['correo_corporativo']);
    $sheet->setCellValue('I' . $row, $asesor['contrasena']);
    $sheet->setCellValue('J' . $row, $asesor['estado']);
    $sheet->setCellValue('K' . $row, formatDate($asesor['solicitud_baja']));
    $sheet->setCellValue('L' . $row, formatDate($asesor['fecha_baja']));
    $sheet->setCellValue('M' . $row, formatDate($asesor['fecha_proxima_eliminacion']));
    $sheet->setCellValue('N' . $row, formatDate($asesor['fecha_respaldo']));
    $sheet->setCellValue('O' . $row, formatDate($asesor['fecha_eliminacion']));
    $sheet->setCellValue('P' . $row, $asesor['comentarios']);
    $row++;
}

// Estilo para todas las celdas con datos (incluyendo encabezados)
$dataRange = 'A1:' . Coordinate::stringFromColumnIndex($colCount) . ($row - 1);
$sheet->getStyle($dataRange)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
]);

// Autoajustar columnas
for ($i = 1; $i <= $colCount; $i++) {
    $column = Coordinate::stringFromColumnIndex($i);
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Descargar
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

function formatDate($fecha) {
    return ($fecha && $fecha !== '0000-00-00') ? date('d/m/Y', strtotime($fecha)) : '';
}
