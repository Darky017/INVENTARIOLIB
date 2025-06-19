<?php
require_once '../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Color\Color;

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $id = isset($_GET['id']) ? trim($_GET['id']) : '';
    if (empty($id)) {
        throw new Exception('ID no proporcionado.');
    }

    $qr = new QrCode($id);
    $qr->setSize(300);
    $qr->setMargin(10);
    $qr->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());
    $qr->setForegroundColor(new Color(0, 128, 0));

    $writer = new PngWriter();
    $result = $writer->write($qr);

    header('Content-Type: '.$result->getMimeType());
    echo $result->getString();
    exit;
} catch (Exception $e) {
    echo "Error al generar el código QR: " . $e->getMessage();
}