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
    <title>Gestion de Alimentos</title>
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
    <h1>Gestión de Tolvas de Alimento</h1>
    <div id="contenedor-tolvas">
        <?php
        for ($i = 1; $i <= 6; $i++) { // Se generan 6 tolvas
            // Consulta modificada para usar la tabla alimentacion
            $query = "SELECT etapa_alim, cantidad_alim, fecha_llegada_alim 
                      FROM alimentacion 
                      WHERE num_tolva = $i 
                      ORDER BY fecha_llegada_alim DESC 
                      LIMIT 1";
            $resultado = $conexion->query($query);

            // Inicializar valores en caso de que no haya datos
            $etapa_alimento = $cantidad_alimento = $fecha_ultimo_relleno = "N/A";
            $toneladas_restantes = $dias_restantes = "N/A";

            if ($resultado && $fila = $resultado->fetch_assoc()) {
                $etapa_alimento = $fila['etapa_alim'];
                $cantidad_alimento = $fila['cantidad_alim'];
                $fecha_ultimo_relleno = $fila['fecha_llegada_alim'];
                
                // Calcular toneladas restantes si hay datos válidos
                if (is_numeric($cantidad_alimento)) {
                    $consumo_diario = 0.2; // 0.2 toneladas por día
                    
                    // Calcular días desde el último relleno
                    $fecha_relleno = new DateTime($fecha_ultimo_relleno);
                    $hoy = new DateTime();
                    $dias_transcurridos = $hoy->diff($fecha_relleno)->days;
                    
                    // Calcular alimento restante
                    $consumo_total = $dias_transcurridos * $consumo_diario;
                    $toneladas_restantes = max(0, $cantidad_alimento - $consumo_total);
                    $dias_restantes = floor($toneladas_restantes / $consumo_diario);
                    
                    // Formatear valores para mostrar
                    $toneladas_restantes = number_format($toneladas_restantes, 2) . " Toneladas";
                    $cantidad_alimento = number_format($cantidad_alimento, 2) . " Toneladas";
                    $fecha_ultimo_relleno = date("d/m/Y H:i", strtotime($fecha_ultimo_relleno));
                }
            }
        ?>
            <div class="tolva">
                <div class="titulo-tolva" onclick="toggleDetalles(<?php echo $i; ?>)">
                    Tolva <?php echo $i; ?> <span id="flecha-<?php echo $i; ?>">▼</span>
                </div>
                <div class="atributos">
                    <span><strong>Etapa:</strong> <?php echo $etapa_alimento; ?></span>
                    <span><strong>Alimento Inicial:</strong> <?php echo $cantidad_alimento; ?></span>
                    <span><strong>Alimento Restante:</strong> <?php echo $toneladas_restantes; ?></span>
                    <span><strong>Días restantes:</strong> <?php echo $dias_restantes; ?></span>
                </div>
                <div class="atributos">
                    <span><strong>Último Relleno:</strong> <?php echo $fecha_ultimo_relleno; ?></span>
                    <span><strong>Consumo diario:</strong> 0.2 Toneladas/día</span>
                </div>
                <div>
                    <button class="boton-verde" onclick="location.href='add_alimento.php?tolva=<?php echo $i; ?>'">Agregar Alimento</button>
                    <button class="boton-amarillo" onclick="location.href='edit_alimento.php?tolva=<?php echo $i; ?>'">Editar Registro</button>
                    <button class="boton-rojo" onclick="vaciarTolva(<?php echo $i; ?>)">Vaciar Tolva</button>
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

<style>
    .titulo-tolva {
        font-size: 24px;
        font-weight: bold;
        background-color: rgb(255, 255, 255);
        color: black;
        padding: 10px;
        margin: 5px 0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 5px;
    }

    .titulo-tolva:hover {
        background-color: rgb(238, 236, 120);
    }

    .tolva {
        margin-bottom: 20px;
    }
</style>

</body>
</html>