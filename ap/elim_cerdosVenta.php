<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}
include("config.php");

if (isset($_GET['caseta'])) {
    $_SESSION['caseta_id'] = $_GET['caseta'];
} elseif (!isset($_SESSION['caseta_id'])) {
    die("No se ha definido la caseta en la sesión ni por parámetro GET.");
}

$id_caseta = $_SESSION['caseta_id'];



$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];


echo $rol;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>Eliminación de Cerdos</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_elim_cerdosVenta.css">

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "cerdos.php": ["cerdos.php", "add_cerdos.php", "elim_cerdosVenta.php", "elim_cerdosMuerte.php"] // Páginas relacionadas con "Cerdos"
                
            };

            sidebarLinks.forEach(link => {
                const href = link.getAttribute("href");

                // Comprueba si la página actual está en las relacionadas
                if (relatedPages[href] && relatedPages[href].includes(currentPath)) {
                    link.classList.add("active");
                } else {
                    link.classList.remove("active");
                }
            });
        });
    </script>

</head>
<body>

        <!-- Navbar -->
        <div class="navbar">
            <h1>GestAP</h1>
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="cerdos.php">Regresar</a>
            </li>
        </div>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

<div class="content d-flex justify-content-center align-items-center" style="margin-left: 200px;">
    <div class="card shadow-sm mt-5" style="min-width: 400px; width: 100%; max-width: 600px;">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0"><i class="fas fa-piggy-bank"></i> Eliminar Cerdos por Venta</h4>
        </div>
        <div class="card-body">
            <form action="eliminar_cerdos.php" method="POST">
                <input type="hidden" name="tipo_eliminacion" value="venta">
                <input type="hidden" name="num_caseta_venta" value="<?php echo htmlspecialchars($id_caseta); ?>">

                <div class="mb-3">
                    <label for="fecha_venta" class="form-label">Fecha de Venta:</label>
                    <input type="datetime-local" name="fecha_venta" id="fecha_venta" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="num_corral_venta" class="form-label">Número de Corral:</label>
                    <input type="number" name="num_corral_venta" id="num_corral_venta" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad de Cerdos a Eliminar:</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-trash-alt"></i> Eliminar por Venta
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>