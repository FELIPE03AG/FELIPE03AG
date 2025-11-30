<?php
ob_start();
session_start();

// Verifica sesión
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

include("config.php");

// Manejo de caseta en sesión / GET
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

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_elim_cerdosVenta_Muerte.css">

    <!-- JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/snippets.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Venta de Cerdos</title>

    <!-- Favicon -->
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon">

    <!-- Script Sidebar -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop();

            const relatedPages = {
                "cerdos.php": [
                    "cerdos.php",
                    "add_cerdos.php",
                    "elim_cerdosVenta.php",
                    "elim_cerdosMuerte.php"
                ]
            };

            sidebarLinks.forEach(link => {
                const href = link.getAttribute("href");

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
    <?php include 'navbar.php'; ?>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Contenido -->
    <div class="content d-flex justify-content-center align-items-center" style="margin-left: 200px;">

        <div class="card shadow-sm mt-5" style="min-width: 400px; width: 100%; max-width: 600px;">

            <div class="card-header bg-warning text-black">
                <h4 class="mb-0">
                    <i class="fas fa-piggy-bank"></i> Eliminar Cerdos por Venta
                </h4>
            </div>

            <div class="card-body">

                <form action="eliminar_cerdos.php" method="POST" class="form-venta">

                    <input type="hidden" name="tipo_eliminacion" value="venta">
                    <input type="hidden" name="num_caseta_venta" value="<?php echo htmlspecialchars($id_caseta); ?>">

                    <div class="mb-3">
                        <label for="fecha_venta" class="form-label">Fecha de Venta:</label>
                        <input type="date" name="fecha_venta" id="fecha_venta" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="num_corral_venta" class="form-label">Número de Corral:</label>
                        <input type="number" name="num_corral_venta" id="num_corral_venta" class="form-control" required>
                        <small class="text-muted">Ingrese corral entre 1-30</small>
                    </div>

                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad de Cerdos a Eliminar:</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <a href="cerdos.php" class="btn btn-secondary w-100">
                                <i class="fas fa-arrow-left"></i> Regresar
                            </a>
                        </div>

                        <div class="col-6">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-trash-alt"></i> Eliminar por Venta
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>

    </div>

<?php if (isset($_GET['success'])): ?>
<script>
Swal.fire({
    title: "¡Operación exitosa!",
    text: "Eliminación de cerdo fue actualizado correctamente.",
    icon: "success",
    showConfirmButton: false,
    timer: 1800,
    timerProgressBar: true
}).then(() => {
    window.location.href = "cerdos.php";
});
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'vacio'): ?>
<script>
Swal.fire({
    icon: 'warning',
    title: 'Corral vacío',
    text: 'No hay cerdos en este corral.'
});
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'exceso'): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Cantidad inválida',
    text: 'La cantidad a vender es mayor a los cerdos existentes.'
});
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'corral_invalido'): ?>
<script>
Swal.fire({
    icon: "error",
    title: "Corral inválido",
    text: "El número de corral debe estar entre 1 y 30.",
    confirmButtonText: "Aceptar"
});
</script>
<?php endif; ?>

</body>
</html>