<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");
$totalCasetas = 6;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <title>Gestion de Alimentos</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_alimentos.css">
</head>

<body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "alimentos.php": ["alimentos.php", "alim.php"] // Páginas relacionadas con "cerdos"
                
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

   

    <div class="content">
    <h1>Gestión de Tolvas de Alimento</h1>
    <div id="contenedor-tolvas">
        <?php
        for ($i = 1; $i <= 6; $i++) { // Se generan 6 tolvas
            $query = "SELECT num_alim, fecha_alim, etapa_alim 
                      FROM tolvas WHERE id = $i";
            $resultado = $conexion->query($query);

            // Inicializar valores en caso de que no haya datos
            $alimento_actual = $llegada_alimento = $tipo_alimento = "N/A";

            if ($resultado && $fila = $resultado->fetch_assoc()) {
                $alimento_actual = number_format($fila['num_alim'] / 1000, 2) . " Toneladas";
                $llegada_alimento = $fila['fecha_alim'];
                $tipo_alimento = $fila['etapa_alim'];
                
            }
        ?>
            <div class="tolva">
                <div class="titulo-tolva" onclick="toggleDetalles(<?php echo $i; ?>)">
                    Tolva <?php echo $i; ?> <span id="flecha-<?php echo $i; ?>">▼</span>
                </div>
                <div class="atributos">
                    <span><strong>Capacidad Total:</strong> 5 Toneladas </span>
                    <span><strong>Alimento Disponible:</strong> <?php echo $alimento_actual; ?> </span>
                    <span><strong>Tipo de Alimento:</strong> <?php echo $tipo_alimento; ?></span>
                    <span><strong>Último Relleno:</strong> <?php echo $llegada_alimento; ?></span>
                </div>
                <div>
                    <button class="boton-verde" onclick="location.href='add_alimentos.php?tolva=<?php echo $i; ?>'">Agregar Alimento</button>
                    <button class="boton-verde" onclick="location.href='edit_tolva.php?tolva=<?php echo $i; ?>'">Editar Tolva</button>
                    <button class="boton-rojo" onclick="vaciarTolva(<?php echo $i; ?>)">Vaciar Tolva</button>
                    <div class="atributos" id="detalles-<?php echo $i; ?>" style="display: none;">
                        
    <h4>Historial de Rellenos:</h4>
    <ul>
        <?php
        $historial_query = "SELECT cantidad_alim, etapa_alim, fecha_llegada_alim 
                            FROM tolvas 
                            WHERE num_tolva = $i 
                            ORDER BY fecha_llegada_alim DESC";

        $historial_result = $conexion->query($historial_query);

        if ($historial_result && $historial_result->num_rows > 0) {
            while ($registro = $historial_result->fetch_assoc()) {
                echo "<li><strong>{$registro['fecha_llegada_alim']}</strong> - {$registro['cantidad_alim']} kg - {$registro['etapa_alim']}</li>";
            }
        } else {
            echo "<li>No hay historial de alimentos para esta tolva.</li>";
        }
        ?>
    </ul>
</div>

                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    function vaciarTolva(tolvaId) {
        if (confirm("¿Estás seguro de que deseas vaciar la tolva " + tolvaId + "?")) {
            fetch("vaciar_tolva.php?tolva=" + tolvaId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Tolva vaciada correctamente.");
                        location.reload();
                    } else {
                        alert("Error al vaciar la tolva: " + data.message);
                    }
                })
                .catch(error => {
                    alert("Ocurrió un error: " + error);
                });
        }
    }

    function toggleDetalles(id) {
        const detalles = document.getElementById(`detalles-${id}`);
        const flecha = document.getElementById(`flecha-${id}`);
        if (detalles.style.display === 'none' || detalles.style.display === '') {
            detalles.style.display = 'block';
            flecha.textContent = '▲';
        } else {
            detalles.style.display = 'none';
            flecha.textContent = '▼';
        }
    }
</script>

    </div>
</body>

</html>