<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];
$caseta = $_GET['caseta'];






// Incluir configuración para la conexión a la base de datos
include("config.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/snippets.js"></script>
    <script src="js/modals.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <title>Agregar Registro - Caseta <?php echo $caseta; ?></title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_add_cerdos.css">
    
    <!-- Estilo para denegar flechas en los cuadros de texto-->
    <style>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

</style>
</head>
<body>
    
    <!-- Navbar -->
    <div class="navbar d-flex justify-content-between align-items-center px-4 py-2 bg-light shadow">
        <h1 class="mb-0">GestAP</h1>

        <!-- Usuario sin dropdown -->
        <div class="d-flex align-items-center">
            <i class="fas fa-user-circle me-2"></i>
            <span><?= htmlspecialchars($nombre) ?></span>
        </div>
    </div>


<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

<!-- Script para búsqueda en vivo -->

<script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "cerdos.php": ["add_cerdos.php", "edit_cerdos.php"] // Páginas relacionadas con "cerdos"
                
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
<div class="content">
    <h2>Agregar Registro - Caseta <?php echo $caseta; ?></h2>
    <form id="form-cerdos" action="save_cerdos.php" method="POST">
        <input type="hidden" name="caseta" value="<?php echo $caseta; ?>">

        <label for="num_cerdos">Cantidad Inicial de Cerdos:</label>
        <input type="number" name="num_cerdos" id="num_cerdos" required>

        <label for="peso_prom">Peso Promedio (kg):</label>
        <input type="number" name="peso_prom" step="0.01" required>

        <label for="edad_prom">Edad Promedio (Semanas):</label>
        <input type="number" name="edad_prom" required>

        <label for="fecha_llegada">Fecha de Llegada:</label>
<input type="date" name="fecha_llegada" value="<?php echo date('Y-m-d'); ?>" readonly required>


        <label for="etapa">Etapa de Alimentación:</label>
        <select name="etapa" required>
            <option value="Iniciador">Iniciador</option>
            <option value="Crecimiento">Crecimiento</option>
            <option value="Desarrollo">Desarrollo</option>
            <option value="Finalizador">Finalizador</option>
        </select>

        <h3>Distribución de Cerdos por Corral</h3>
        <?php for ($i = 1; $i <= 30; $i++) { ?>
            <label for="corral_<?php echo $i; ?>">Corral <?php echo $i; ?>:</label>
            <input type="number" name="corral_<?php echo $i; ?>" id="corral_<?php echo $i; ?>" required>
        <?php } ?>

        <button type="submit">Guardar</button>
    </form>

    <script>
        document.getElementById('form-cerdos').addEventListener('submit', function(e) {
            const cantidadInicial = parseInt(document.getElementById('num_cerdos').value);
            let sumaCorrales = 0;

            for (let i = 1; i <= 30; i++) {
                const valor = parseInt(document.getElementById('corral_' + i).value) || 0;
                sumaCorrales += valor;
            }

            if (sumaCorrales !== cantidadInicial) {
                e.preventDefault();
                alert('⚠️ La suma de los cerdos en los corrales (' + sumaCorrales + ') no coincide con la cantidad inicial (' + cantidadInicial + ').');
            }
        });
    </script>
</div>

</form>

</body>
</html>