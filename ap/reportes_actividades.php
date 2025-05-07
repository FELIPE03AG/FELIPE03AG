<?php
ob_start();
session_start();

if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
    exit();
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");

// Funciones para gráficas
function getEliminacionesPorCaseta($conexion) {
    $data = [];

    $query = "SELECT num_caseta, SUM(cantidad) as total FROM eliminacion_venta GROUP BY num_caseta";
    $result = $conexion->query($query);
    while ($row = $result->fetch_assoc()) {
        $data["Caseta " . $row['num_caseta']] = (int)$row['total'];
    }

    $query2 = "SELECT num_caseta, COUNT(*) as total FROM eliminacion_muerte GROUP BY num_caseta";
    $result2 = $conexion->query($query2);
    while ($row = $result2->fetch_assoc()) {
        $key = "Caseta " . $row['num_caseta'];
        $data[$key] = ($data[$key] ?? 0) + (int)$row['total'];
    }

    return $data;
}

function getEliminacionesPorFecha($conexion) {
    $data = [];
    $query = "
        SELECT fecha_venta AS fecha, SUM(cantidad) as total FROM eliminacion_venta GROUP BY fecha
        UNION ALL
        SELECT fecha_muerte AS fecha, COUNT(*) as total FROM eliminacion_muerte GROUP BY fecha
    ";
    $result = $conexion->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[$row['fecha']] = ($data[$row['fecha']] ?? 0) + (int)$row['total'];
    }
    ksort($data);
    return $data;
}

$query_ventas = "SELECT fecha_venta, cantidad, num_caseta, num_corral FROM eliminacion_venta ORDER BY fecha_venta DESC";
$result_ventas = $conexion->query($query_ventas);

$query_muertes = "SELECT fecha_muerte, num_caseta, num_corral, causa_muerte FROM eliminacion_muerte ORDER BY fecha_muerte DESC";
$result_muertes = $conexion->query($query_muertes);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Actividades</title>

    <!-- Estilos -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  

    <style>
        body {
            background-image: url('img/f.jpeg');
            background-size: cover;
            background-position: center;
        }

        .navbar {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 60px;
            background-color: #f0f0f0;
            color: black;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .sidebar {
            position: fixed;
            top: 60px; left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #f0f0f0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: black;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #6e6e6e;
        }

        .sidebar a.active {
            background-color: #4caf50;
            font-weight: bold;
        }

        .content {
            margin-top: 60px;
            margin-left: 250px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 60px);
        }

        h2, h3 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            min-width: 100px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .hidden {
            display: none;
        }

        button {
            margin: 10px 10px 10px 0;
        }
    </style>

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

        function toggleSection(id) {
            document.getElementById(id).classList.toggle("hidden");
        }

        function generarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            let y = 10;

            doc.text("📋 Reporte de Eliminaciones", 10, y);
            y += 10;

            doc.text("➡ Eliminaciones por Venta", 10, y);
            y += 10;

            <?php
            $result_ventas->data_seek(0);
            while ($fila = $result_ventas->fetch_assoc()) {
                echo "doc.text('Fecha: {$fila['fecha_venta']} | Caseta: {$fila['num_caseta']} | Corral: {$fila['num_corral']} | Cantidad: {$fila['cantidad']}', 10, y); y += 10;\n";
            }
            ?>

            y += 10;
            doc.text("➡ Eliminaciones por Muerte", 10, y);
            y += 10;

            <?php
            $result_muertes->data_seek(0);
            while ($fila = $result_muertes->fetch_assoc()) {
                echo "doc.text('Fecha: {$fila['fecha_muerte']} | Caseta: {$fila['num_caseta']} | Corral: {$fila['num_corral']} | Causa: {$fila['causa_muerte']}', 10, y); y += 10;\n";
            }
            ?>

            doc.save("Reporte_Eliminaciones.pdf");
        }
    </script>
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1>GestAP</h1>
        <div class="user-name"><?= htmlspecialchars($nombre) ?></div>
    </div>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

        

    <!-- Content -->
    <div class="content">
        <div class="container">
            <h2>Reportes de Eliminación</h2>

            <button class="btn btn-outline-primary" onclick="toggleSection('tablaVentas')">📋 Ver Tabla de Ventas</button>
            <button class="btn btn-outline-danger" onclick="toggleSection('tablaMuertes')">📋 Ver Tabla de Muertes</button>
            <button class="btn btn-outline-info" onclick="toggleSection('graficas')">📊 Ver Gráficos</button>
            <button class="btn btn-outline-success" onclick="generarPDF()">📝 Descargar Reporte</button>

            <!-- Tabla de Ventas -->
            <div id="tablaVentas" class="hidden">
                <h3>Eliminaciones por Venta</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Caseta</th>
                            <th>Corral</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $result_ventas->data_seek(0); while ($fila = $result_ventas->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $fila['fecha_venta'] ?></td>
                                <td><?= $fila['num_caseta'] ?></td>
                                <td><?= $fila['num_corral'] ?></td>
                                <td><?= $fila['cantidad'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Tabla de Muertes -->
            <div id="tablaMuertes" class="hidden">
                <h3>Eliminaciones por Muerte</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Caseta</th>
                            <th>Corral</th>
                            <th>Causa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $result_muertes->data_seek(0); while ($fila = $result_muertes->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $fila['fecha_muerte'] ?></td>
                                <td><?= $fila['num_caseta'] ?></td>
                                <td><?= $fila['num_corral'] ?></td>
                                <td><?= $fila['causa_muerte'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Gráficas -->
            <div id="graficas" class="hidden">
                <h3>Eliminaciones por Caseta</h3>
                <canvas id="eliminacionesCaseta"></canvas>

                <h3>Eliminaciones en el Tiempo</h3>
                <canvas id="eliminacionesFecha"></canvas>
            </div>
        </div>
    </div>
</body>
</html>
