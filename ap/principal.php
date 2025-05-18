<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

echo $rol;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/style_principal.css">
    <script src="js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop();

            sidebarLinks.forEach(link => {
                link.classList.remove("active");
                if (link.getAttribute("href") === currentPath) {
                    link.classList.add("active");
                }
            });
        });
    </script>
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <h1>GestAP</h1>
        <div class="user-name">
            <?= htmlspecialchars($nombre) ?>
        </div>
    </div>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content text-center">
        <h1 class="fw-bold">Bienvenido a GestAP</h1>
        <p class="fs-5">Tu mejor herramienta para la gestión agropecuaria en la industria.</p>
        <img src="img/image_fondo.png" alt="Imagen de fondo" class="img-fluid mt-4 rounded" style="max-width: 900px;">
    </div>
</body>

</html>