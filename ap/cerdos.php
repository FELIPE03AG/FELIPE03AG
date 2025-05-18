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

function calcularEtapa($fecha_llegada) {
    $hoy = new DateTime();
    $fecha = new DateTime($fecha_llegada);
    $dias = (int)$hoy->diff($fecha)->format('%a');

    if ($dias <= 30) {
        return "Iniciador";
    } elseif ($dias <= 60) {
        return "Crecimiento";
    } elseif ($dias <= 90) {
        return "Desarrollo";
    } else {
        return "Finalizador";
    }
}
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
    <title>Gestión de Cerdos</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_cerdos.css">
</head>

<body>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "cerdos.php": ["cerdos.php", "add_cerdos.php", "elim_cerdosVenta.php", "elim_cerdosMuerte.php"] // Páginas relacionadas con "cerdos"

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

    <!-- Usuario con dropdown -->
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle me-2"></i>
            <?= htmlspecialchars($nombre) ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
        </ul>
    </div>
</div>


    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>



    <div class="content">

        <h1>Gestión de Casetas de Cerdos</h1>
        <div id="contenedor-casetas">
            <?php
            for ($i = 1; $i <= $totalCasetas; $i++) {
                // Consulta de datos de la caseta con los nombres de columnas correctos
                $query = "SELECT num_cerdos, peso_promedio, edad_promedio, fecha_llegada, etapa_alimentacion 
                  FROM casetas WHERE id = $i";
                $resultado = $conexion->query($query);

                // Inicializar valores en caso de que no haya datos
                $cantidad_cerdos = $peso_promedio = $edad_promedio = $fecha_llegada = $etapa_alimentacion = "N/A";

                if ($resultado && $fila = $resultado->fetch_assoc()) {
                    $cantidad_cerdos = $fila['num_cerdos'];
                    $peso_promedio = $fila['peso_promedio'];
                    $edad_promedio = $fila['edad_promedio'];
                    $fecha_llegada = $fila['fecha_llegada'];
                    $etapa_actual = $fila['etapa_alimentacion'];
$etapa_calculada = calcularEtapa($fecha_llegada);

// Si la etapa calculada es diferente a la almacenada, actualizar en BD
if ($etapa_calculada !== $etapa_actual) {
    $update_query = "UPDATE casetas SET etapa_alimentacion = '$etapa_calculada' WHERE id = $i";
    $conexion->query($update_query);
}

$etapa_alimentacion = $etapa_calculada; // Mostrar la nueva etapa

                }
            ?>
                <div class="caseta">
                    <div class="titulo-caseta" onclick="toggleCorrales(<?php echo $i; ?>)">
                        Caseta <?php echo $i; ?> <span id="flecha-<?php echo $i; ?>">▼</span>
                    </div>
                    <div class="atributos">
                        <span><strong>Cantidad Inicial:</strong> <?php echo $cantidad_cerdos; ?></span>
                        <span><strong>Fecha:</strong> <?php echo $fecha_llegada; ?></span>
                        <span><strong>Peso Promedio:</strong> <?php echo $peso_promedio; ?> kg</span>
                        <span><strong>Edad Promedio:</strong> <?php echo $edad_promedio; ?> semanas</span>
                        <span><strong>Etapa:</strong> <?php echo $etapa_alimentacion; ?></span>
                    </div>
                    <div>
                        <button class="boton-verde" onclick="location.href='add_cerdos.php?caseta=<?php echo $i; ?>'">Agregar Registro</button>
                        <button class="boton-amarillo" onclick="location.href='elim_cerdosVenta.php?caseta=<?php echo $i; ?>'">Venta de Cerdos</button>
                        <button class="boton-amarillo" onclick="location.href='elim_cerdosMuerte.php?caseta=<?php echo $i; ?>'">Muerte de Cerdos</button>
                        <button class="boton-rojo" onclick="vaciarCaseta(<?php echo $i; ?>)">Vaciar Caseta</button>
                    </div>
                    <div id="corrales-<?php echo $i; ?>" class="corrales" style="display: none;">
                        <?php
                        // Obtener el total de cerdos en la caseta
                        $query_total_cerdos = "SELECT SUM(num_cerdos) AS total_cerdos FROM corrales WHERE caseta_id = $i";
                        $resultado_total = $conexion->query($query_total_cerdos);
                        $fila_total = $resultado_total->fetch_assoc();
                        $total_cerdos = $fila_total['total_cerdos'] ?? 0; // Si no hay cerdos, mostrar 0
                        ?>

                        <!-- Mostrar total de cerdos fuera de la tabla -->
                        <div style="margin-bottom: 10px; font-size: 18px; font-weight: bold; color: #333;">
                            🐷 Total de Cerdos en la Caseta: <span style="color: #28a745;"><?php echo $total_cerdos; ?></span>
                        </div>

                        <table>
                            <tr>
                                <th>Corral</th>
                                <th>Número de Cerdos</th>
                            </tr>
                            <?php
                            // Consulta para obtener los corrales de la caseta actual ordenados del 1 al 30
                            $query_corrales = "SELECT numero_corral, num_cerdos FROM corrales WHERE caseta_id = $i ORDER BY numero_corral ASC";
                            $resultado_corrales = $conexion->query($query_corrales);

                            while ($corral = $resultado_corrales->fetch_assoc()) {
                                echo "<tr><td>Corral " . $corral['numero_corral'] . "</td><td>" . $corral['num_cerdos'] . "</td></tr>";
                            }
                            ?>
                        </table>
                    </div>



                </div>
            <?php } ?>
        </div>

        <script>
            function vaciarCaseta(casetaId) {
                if (confirm("¿Estás seguro de que deseas vaciar la caseta " + casetaId + "?")) {
                    // Realiza la solicitud al archivo PHP para vaciar la caseta
                    fetch("vaciar_caseta.php?caseta=" + casetaId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Caseta vaciada correctamente.");
                                location.reload(); // Recargar la página para actualizar los datos
                            } else {
                                alert("Error al vaciar la caseta: " + data.message);
                            }
                        })
                        .catch(error => {
                            alert("Ocurrió un error: " + error);
                        });
                }
            }

            function toggleCorrales(id) {
                const corrales = document.getElementById(`corrales-${id}`);
                const flecha = document.getElementById(`flecha-${id}`);
                if (corrales.style.display === 'none' || corrales.style.display === '') {
                    corrales.style.display = 'block';
                    flecha.textContent = '▲'; // Flecha hacia arriba
                } else {
                    corrales.style.display = 'none';
                    flecha.textContent = '▼'; // Flecha hacia abajo
                }
            }
        </script>
    </div>
</body>
</html>