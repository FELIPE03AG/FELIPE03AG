<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];



// Incluir configuración para la conexión a la base de datos
include("config.php");

$totalCasetas = 6;



// Función para calcular la etapa de alimentación basado en la fecha de llegada
function calcularEtapa($fecha_llegada) {
    // Crear objetos DateTime
    $hoy = new DateTime();
    $fecha = new DateTime($fecha_llegada);
    
    // Obtener la diferencia en días
    $dias = (int)$hoy->diff($fecha)->format('%a');
    
    // Según la cantidad de días, definir la etapa:
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

    <!-- Script de Bootstrap JavaScript -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <title>Gestion de Cerdos</title>
</head>

<body>
    <style>
        body {
            background-image: url('img/f.jpeg');
            background-size: cover;
            /* para cubrir todo el fondo */
            background-position: center;
            /* para centrar la imagen */
            /* Añade más estilos si es necesario */
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: #f0f0f0;
            /* Gris oscuro */
            color: black;
            display: flex;
            justify-content: 'between';
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .navbar h1 {
            margin: 0;
            font-size: 20px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px;
            /* Debajo del navbar */
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #f0f0f0;
            /* Gris medio */
            color: black;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
        }

        .sidebar a {
            color: black;
            padding: 15px 20px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #6e6e6e;
            /* Gris oscuro para el hover */
        }

        /* Resaltar el apartado activo */
        .sidebar a.active {
            background-color: #4caf50;
            /* Verde resalte */
            color: black;
            font-weight: bold;
        }


        /* Content */
        .content {
            margin-top: 60px;
            /* Espacio debajo del navbar */
            margin-left: 250px;
            /* Espacio para el sidebar */
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            color: #333;
            min-height: calc(100vh - 60px);
            /* Asegura que el contenido llene el espacio */
        }

        /* Estilo de texto */
        .content h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            /* Espacio entre tablas */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            min-width: 100px;
            /* Ancho mínimo para celdas */
        }

        th {
            background-color: #f2f2f2;
            /* Color de fondo para encabezados */
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
            /* Color de fondo para filas pares */
        }
    </style>

<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        #contenedor-casetas {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .caseta {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 90%;
            max-width: 800px;
            margin: 10px;
        }

        .titulo-caseta {
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
            color: #333;
        }

        .atributos {
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            margin: 5px;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .boton-verde {
            background-color: #28a745;
        }


        .boton-verde:hover {
            background-color: #218838;
        }

        .boton-amarillo {
            background-color:rgb(255, 187, 0);
        }
        .boton-amarillo:hover {
            background-color:rgb(223, 163, 0);
        }

        .boton-rojo {
            background-color: #dc3545;
        }

        .boton-rojo:hover {
            background-color: #c82333;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "cerdos.php": ["cerdos.php", "add_cerdos.php, elim_cerdos.php"] // Páginas relacionadas con "cerdos"
                
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
   

    <!-- tab bar-->
    <div class="navbar">
        <h1>GestAP</h1>


        <form class="d-flex">
            <input class="form-control me-2" type="search" placeholder="Buscar registros..." aria-label="Buscar" id="buscar">
        </form>
        <div>
            <div class="user-name">
                <?= htmlspecialchars($nombre) ?>
            </div>
        </div>
    </div>


    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

   

    <div class="content">
    <h1>Gestión de Granjas Porcinas</h1>
<div id="contenedor-casetas">
    <?php
    for ($i = 1; $i <= $totalCasetas; $i++) {
        // Consulta para obtener los datos de la caseta
        $query = "SELECT num_cerdos, peso_promedio, edad_promedio, fecha_llegada, etapa_alimentacion 
                  FROM casetas WHERE id = $i";
        $resultado = $conexion->query($query);
    
        // Valores por defecto en caso de no encontrar datos
        $cantidad_cerdos = $peso_promedio = $edad_promedio = $fecha_llegada = $etapa_alimentacion = "N/A";
        
        if ($resultado && $fila = $resultado->fetch_assoc()) {
            $cantidad_cerdos  = $fila['num_cerdos'];
            $peso_promedio    = $fila['peso_promedio'];
            $edad_promedio    = $fila['edad_promedio'];  
            $fecha_llegada    = $fila['fecha_llegada'];
            
            // Calcular la nueva etapa usando la fecha de llegada
            $nueva_etapa = calcularEtapa($fecha_llegada);
            
            // Si la etapa actual de la BD es distinta, la actualizamos
            if ($fila['etapa_alimentacion'] !== $nueva_etapa) {
                $update = "UPDATE casetas SET etapa_alimentacion = '$nueva_etapa' WHERE id = $i";
                $conexion->query($update);
            }
            
            // Se utiliza la etapa recién calculada para mostrar
            $etapa_alimentacion = $nueva_etapa;
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
                <button class="boton-verde" onclick="location.href='edit_cerdos.php?caseta=<?php echo $i; ?>'">Editar Registro</button>
                <button class="boton-amarillo" onclick="location.href='elim_cerdos.php?caseta=<?php echo $i; ?>'">Eliminar Cerdos</button>
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
        const corrales = document.getElementById(corrales-${id});
        const flecha = document.getElementById(flecha-${id});
        if (corrales.style.display === 'none' || corrales.style.display === '') {
            corrales.style.display = 'block';
            flecha.textContent = '▲';  // Flecha hacia arriba
        } else {
            corrales.style.display = 'none';
            flecha.textContent = '▼';  // Flecha hacia abajo
        }
    }
</script>

<style>
    .titulo-caseta {
        font-size: 24px;
        font-weight: bold;
        background-color:rgb(255, 255, 255);
        color: black;
        padding: 10px;
        margin: 5px 0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 5px;
    }

    .titulo-caseta:hover {
        background-color:rgb(148, 238, 152);
    }

    .corrales {
        margin: 10px 0;
        padding: 10px;
        background-color: #f1f1f1;
        border-radius: 5px;
    }

    .caseta {
        margin-bottom: 20px;
    }

    .flecha {
        margin-left: 10px;
    }
</style>




    </div>



</body>

</html>