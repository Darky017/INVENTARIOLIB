<?php
session_start();
require_once '../config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!$username || !$password) {
        $errors[] = "Ingresa tu usuario y contraseña.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM superusuarios WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['superusuario'] = [
                'id' => $user['id'], 'username' => $user['username'],
                'nombre' => $user['nombre'], 'apellido' => $user['apellido'],
                'created_at' => $user['created_at']
            ];
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Usuario o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login | Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
<link rel="stylesheet" href="../assets/vendor/remixicon/fonts/remixicon.css">
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="shortcut icon" href="../assets/images/favicon.ico" />
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" href="../assets/vendor/remixicon/fonts/remixicon.css">
    <style>
        body {
            background: url('https://wallpaperaccess.com/full/3979449.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
        }
        body::before { content: ""; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: -1; }
        .login-container {
            background: rgba(255, 255, 255, 0.9); padding: 40px; border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); margin-top: 80px; text-align: center;
        }
        .login-logo { width: 100px; margin-bottom: 20px; }
        .btn-login {
            background: linear-gradient(45deg, #007B33, #00a651); border: none; border-radius: 8px;
            padding: 10px 20px; font-weight: 600; color: white; transition: 0.3s;
        }
        .btn-login:hover { background: linear-gradient(45deg, #00692d, #008d45); }
        .alert { border-radius: 8px; }
        @media (max-width: 768px) { .login-container { margin: 20px; padding: 30px; } }
    </style>
</head>
<body>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-lg-4 col-md-6">
        <div class="card login-container">
            <div class="d-flex justify-content-center">
                <img src="https://avisoprivacidad.grupolibera.mx/elementos/Libera.png" alt="Grupo Libera" class="login-logo">
            </div>
            <h3 class="mb-3">Iniciar Sesión</h3>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger"><ul class="mb-0">
                    <?php foreach ($errors as $error) echo "<li>".htmlspecialchars($error)."</li>"; ?>
                </ul></div>
            <?php endif; ?>
            <form action="auth-sign-in.php" method="POST">
                <input type="text" name="username" class="form-control mb-2" placeholder="Usuario" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Contraseña" required>
                <button type="submit" class="btn btn-login btn-block">Ingresar</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/backend-bundle.min.js"></script>
<script src="../assets/js/app.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.lineicons.com/3.0/lineicons.css">
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
