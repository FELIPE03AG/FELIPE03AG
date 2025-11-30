<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];
$caseta = $_GET['caseta'];

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Agregar Registro - Caseta <?php echo $caseta; ?></title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_add_cerdos.css">
    
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
<?php include 'navbar.php'; ?>

<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

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

    <h2 class="text-center mb-4">
        <i class="fas fa-pen"></i> Agregar Registro - Caseta <?php echo $caseta; ?>
    </h2>

    <form id="form-cerdos" action="save_cerdos.php" method="POST">
        <input type="hidden" name="caseta" value="<?php echo $caseta; ?>">

        <div class="section">
            <h4><i class="fas fa-info-circle"></i> Datos generales</h4>
            <hr>

            <label for="num_cerdos">Cantidad Inicial de Cerdos</label>
            <input type="number" name="num_cerdos" id="num_cerdos" min="1" required>

            <label for="peso_prom">Peso Promedio (kg)</label>
            <input type="number" name="peso_prom" step="0.01" min="1" required>

            <label for="edad_prom">Edad Promedio (Semanas)</label>
            <input type="number" name="edad_prom" min="1" required>

            <label for="fecha_llegada">Fecha de Llegada</label>
            <input type="date" name="fecha_llegada" value="<?php echo date('Y-m-d'); ?>" readonly required>

            <label for="etapa">Etapa de Alimentación</label>
            <select name="etapa" required>
                <option value="Iniciador">Iniciador</option>
                <option value="Crecimiento">Crecimiento</option>
                <option value="Desarrollo">Desarrollo</option>
                <option value="Finalizador">Finalizador</option>
            </select>
        </div>

        <div class="section">
            <h4><i class="fas fa-th"></i> Distribución por Corrales</h4>
            <hr>

            <div class="corrales-grid">
                <?php for ($i = 1; $i <= 30; $i++) { ?>
                    <div class="corral-item">
                        <label for="corral_<?php echo $i; ?>">Corral <?php echo $i; ?></label>
                        <input type="number" name="corral_<?php echo $i; ?>" id="corral_<?php echo $i; ?>" min="0" max="30" required>
                    </div>
                <?php } ?>
            </div>
        </div>

        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Guardar Registro
        </button>

        <a href="cerdos.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </form>
</div>

<script>
document.getElementById('num_cerdos').addEventListener('change', function() {
    let total = parseInt(this.value) || 0;
    let promedio = Math.floor(total / 30);
    let resto = total % 30;

    for (let i = 1; i <= 30; i++) {
        document.getElementById('corral_' + i).value = promedio + (i <= resto ? 1 : 0);
    }
});

// Recalcular total si el usuario modifica manualmente los corrales
for (let i = 1; i <= 30; i++) {
    document.getElementById('corral_' + i).addEventListener('input', function() {

        let suma = 0;
        for (let j = 1; j <= 30; j++) {
            suma += parseInt(document.getElementById('corral_' + j).value) || 0;
        }

        document.getElementById('num_cerdos').value = suma;
    });
}
</script>

<?php if (isset($_GET['success'])) { ?>
<script>
Swal.fire({
  title: '¡Registro exitoso!',
  text: 'Los datos fueron guardados correctamente',
  icon: 'success',
  confirmButtonText: 'Aceptar'
}).then((result) => {
  if (result.isConfirmed) {
    window.location.href = "cerdos.php";
  }
});
</script>
<?php } ?>

</body>
</html>