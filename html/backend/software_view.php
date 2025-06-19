<?php
// software_view.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';

// 🚨 Bloqueo de acceso sin sesión activa
if (!isset($_SESSION['superusuario'])) {
    header("Location: auth-sign-in.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<p class='text-danger'>ID no válido.</p>";
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM software WHERE equipo_id = :id");
$stmt->execute(['id' => $id]);
$software = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php if ($software): ?>
    <p><strong>RustDesk ID:</strong> <?= htmlspecialchars($software['rustdesk_id']); ?></p>
    <p><strong>Contraseña:</strong> <?= htmlspecialchars($software['rustdesk_password']); ?></p>
<?php else: ?>
    <p class='text-warning'>Sin información de software.</p>
<?php endif; ?>

<a href='software_add.php?id=<?= $id; ?>' class='btn btn-primary'>Registrar Software</a>
