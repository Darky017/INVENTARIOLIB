<?php
// header.php
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['superusuario']) && $current_page !== 'auth-sign-in.php') {
    header("Location: auth-sign-in.php");
    exit();
}

$usuario = $_SESSION['superusuario']['nombre'] ?? 'Usuario';
$fecha_creacion = $_SESSION['superusuario']['created_at'] ?? null;
$fecha_creacion = ($fecha_creacion && strtotime($fecha_creacion) > 0)
    ? date("d M, Y", strtotime($fecha_creacion))
    : "Fecha desconocida";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Grupo Libera</title>
    <link rel="shortcut icon" href="../assets/images/favicon.ico" />
    <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" href="../assets/vendor/remixicon/fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.lineicons.com/3.0/lineicons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background-color: #000;
            border-bottom: 2px solid #00813a;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 600;
            color: #00813a;
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        .nav-link {
            color:rgb(255, 255, 255);
            font-weight: 500;
            margin-right: 15px;
        }
        .nav-link:hover {
            color:rgb(41, 155, 92);
        }
        .dropdown-menu {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .user-info {
            text-align: center;
            padding: 15px;
        }
        .user-info img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .user-info h6 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
        .user-info p {
            font-size: 12px;
            color: #666;
        }
        .btn-logout {
            font-size: 12px;
            padding: 6px 12px;
        }
        .navbar .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<!-- Loader -->
<div id="loading"><div id="loading-center"></div></div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="https://avisoprivacidad.grupolibera.mx/elementos/Libera.png" alt="Logo Grupo Libera">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" 
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="asesores_list.php">Asesores</a></li>
                <li class="nav-item"><a class="nav-link" href="usuarios_list.php">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" href="equipos_list.php">Equipos</a></li>
                <li class="nav-item"><a class="nav-link" href="celulares_list.php">Celulares</a></li>
                <li class="nav-item"><a class="nav-link" href="sims_list.php">SIMs</a></li>
                <li class="nav-item"><a class="nav-link" href="tablets_list.php">Tablets</a></li>
                <li class="nav-item"><a class="nav-link" href="tv_list.php">TVs</a></li>
                <li class="nav-item"><a class="nav-link" href="perifericos_list.php">Periféricos</a></li>
                <li class="nav-item"><a class="nav-link" href="impresoras_list.php">Impresoras</a></li>
                <li class="nav-item"><a class="nav-link" href="responsivas_list.php">Responsivas</a></li>
            </ul>
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                   data-toggle="dropdown" aria-expanded="false">
                    <img src="../assets/images/user/1.png" alt="Usuario" class="avatar-50 rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <div class="user-info" style="padding: 10px 20px 10px 20px; min-width: 180px;">
                        <h6><?php echo htmlspecialchars($usuario); ?></h6>
                        <p>Desde <?php echo htmlspecialchars($fecha_creacion); ?></p>
                        <a href="logout.php" class="btn btn-danger btn-logout">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<br>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/backend-bundle.min.js"></script>
<script src="../assets/js/app.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("loading").style.display = "none";
    });

    // Auto logout tras inactividad
    const tiempoInactivo = 600000; // 5 minutos
    let temporizador = setTimeout(() => window.location.href = 'logout.php', tiempoInactivo); // <- CAMBIO AQUÍ

    const reiniciarTemporizador = () => {
        clearTimeout(temporizador);
        temporizador = setTimeout(() => window.location.href = 'logout.php', tiempoInactivo); // <- CAMBIO AQUÍ TAMBIÉN
    };

    document.addEventListener("mousemove", reiniciarTemporizador);
    document.addEventListener("keydown", reiniciarTemporizador);
</script>

</body>
</html>
