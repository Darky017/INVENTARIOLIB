<?php
require_once '../config.php'; // Ajusta la ruta según tu estructura

$username = ""; // Cambia esto al usuario que quieres actualizar
$newPassword = "."; // Tu contraseña real

// Encriptar la nueva contraseña
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Actualizar la contraseña en la base de datos
$stmt = $pdo->prepare("UPDATE superusuarios SET password = :password WHERE username = :username");
$stmt->execute(['password' => $hashedPassword, 'username' => $username]);

echo "✅ Contraseña actualizada correctamente en la base de datos.";
?>
