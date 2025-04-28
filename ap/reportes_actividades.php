<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

echo $rol;

include("config.php"); // Conexión a la base de datos

// Obtener eliminaciones por venta
$query_ventas = "SELECT fecha_venta, cantidad, num_caseta, num_corral, usuario FROM eliminacion_venta ORDER BY fecha_venta DESC";
$result_ventas = $conexion->query($query_ventas);

// Obtener eliminaciones por muerte
$query_muertes = "SELECT fecha_muerte, num_caseta, num_corral, causa_muerte, usuario FROM eliminacion_muerte ORDER BY fecha_muerte DESC";
$result_muertes = $conexion->query($query_muertes);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>Reporte de Actividades</title>
</head>
<body>

<style>
   body {
            background-image: url('img/f.jpeg');
            background-size: cover; /* para cubrir todo el fondo */
            background-position: center; /* para centrar la imagen */
            /* Añade más estilos si es necesario */
        }
        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: #f0f0f0; /* Gris oscuro */
            color: black;
            display: flex;
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
            top: 60px; /* Debajo del navbar */
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #f0f0f0; /* Gris medio */
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
            background-color: #6e6e6e; /* Gris oscuro para el hover */
        }
         /* Resaltar el apartado activo */
        .sidebar a.active {
            background-color: #4caf50; /* Verde resalte */
            color: black;
            font-weight: bold;
        }


        /* Content */
        .content {
            margin-top: 60px; /* Espacio debajo del navbar */
            margin-left: 250px; /* Espacio para el sidebar */
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            color: #333;
            min-height: calc(100vh - 60px); /* Asegura que el contenido llene el espacio */
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
        margin-bottom: 20px; /* Espacio entre tablas */
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        min-width: 100px; /* Ancho mínimo para celdas */
    }
    th {
        background-color: #f2f2f2; /* Color de fondo para encabezados */
    }
    tr:nth-child(even) {
        background-color: #f2f2f2; /* Color de fondo para filas pares */
    }
  </style>
    
  <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual (home.php, services.php, etc.)

            sidebarLinks.forEach(link => {
                // Elimina la clase activa de todos los enlaces
                link.classList.remove("active");

                // Agrega la clase activa al enlace correspondiente
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
        <div>
         <div class="user-name">
    <?= htmlspecialchars($nombre) ?>
</div>
         </div>
    </div>

     <!-- Sidebar -->
     <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">

  
<div class="container">
    <h2>Reportes de Eliminación</h2>

    <button onclick="toggleSection('tablaVentas')">📋 Ver Tabla de Ventas</button>
    <button onclick="toggleSection('tablaMuertes')">📋 Ver Tabla de Muertes</button>
    <button onclick="toggleSection('graficas')">📊 Ver Gráficos</button>
    <button onclick="generarPDF()">📝 Descargar Reporte</button>

    <!-- Tabla de ventas -->
    <div id="tablaVentas" class="hidden">
        <h3>Eliminaciones por Venta</h3>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Caseta</th>
                <th>Corral</th>
                <th>Cantidad</th>
            </tr>
            <?php while ($fila = $result_ventas->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $fila['fecha_venta']; ?></td>
                    <td><?php echo $fila['num_caseta']; ?></td>
                    <td><?php echo $fila['num_corral']; ?></td>
                    <td><?php echo $fila['cantidad']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Tabla de muertes -->
    <div id="tablaMuertes" class="hidden">
        <h3>Eliminaciones por Muerte</h3>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Caseta</th>
                <th>Corral</th>
                <th>Causa</th>
            </tr>
            <?php while ($fila = $result_muertes->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $fila['fecha_muerte']; ?></td>
                    <td><?php echo $fila['num_caseta']; ?></td>
                    <td><?php echo $fila['num_corral']; ?></td>
                    <td><?php echo $fila['causa_muerte']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Gráficos -->
    <div id="graficas" class="hidden">
        <h3>Eliminaciones por Caseta</h3>
        <canvas id="eliminacionesCaseta"></canvas>
        
        <h3>Eliminaciones en el Tiempo</h3>
        <canvas id="eliminacionesFecha"></canvas>
    </div>
</div>

<script>
    function toggleSection(id) {
        let section = document.getElementById(id);
        section.classList.toggle("hidden");
    }

    function generarPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.text("Reporte de Eliminaciones", 10, 10);

        let y = 20;
        doc.text("📋 Eliminaciones por Venta", 10, y);
        y += 10;

        <?php
        $result_ventas->data_seek(0); // Reiniciar el puntero
        while ($fila = $result_ventas->fetch_assoc()) {
            echo "doc.text('Fecha: " . $fila['fecha_venta'] . " | Caseta: " . $fila['num_caseta'] . " | Corral: " . $fila['num_corral'] . " | Cantidad: " . $fila['cantidad'] . "', 10, y);\n";
            echo "y += 10;\n";
        }
        ?>

        y += 10;
        doc.text("📋 Eliminaciones por Muerte", 10, y);
        y += 10;

        <?php
        $result_muertes->data_seek(0);
        while ($fila = $result_muertes->fetch_assoc()) {
            echo "doc.text('Fecha: " . $fila['fecha_muerte'] . " | Caseta: " . $fila['num_caseta'] . " | Corral: " . $fila['num_corral'] . " | Causa: " . $fila['causa_muerte'] . "', 10, y);\n";
            echo "y += 10;\n";
        }
        ?>

        doc.save("Reporte_Eliminaciones.pdf");
    }
</script>



    
    </div>
    





    
    
</body>
</html>