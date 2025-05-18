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

function getCausasDeMuerte($conexion) {
    $data = [];
    $query = "SELECT causa_muerte, COUNT(*) as total FROM eliminacion_muerte GROUP BY causa_muerte";
    $result = $conexion->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[$row['causa_muerte']] = (int)$row['total'];
    }
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
  <link rel="icon" href="img/cerdo.ico" type="image/x-icon">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- Estilos -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">

    <style>
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

        

    <!-- Content -->
    <div class="content">
        <div class="container">
            <h2>Reportes de Eliminación</h2>

            <button class="btn btn-outline-primary" onclick="toggleSection('tablaVentas')">📋 Ver Tabla de Ventas</button>
            <button class="btn btn-outline-danger" onclick="toggleSection('tablaMuertes')">📋 Ver Tabla de Muertes</button>
            <button class="btn btn-outline-info" onclick="toggleSection('graficas')">📊 Ver Gráficos</button>
            <button onclick="generarPDF()">📄 Descargar Reporte PDF</button>


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

                <h3>Causas de Muerte</h3>
                <canvas id="causasMuerte"></canvas>

            </div>
        </div>
    </div>
    <script>
    const datosCaseta = <?= json_encode(getEliminacionesPorCaseta($conexion)); ?>;
    const datosFecha = <?= json_encode(getEliminacionesPorFecha($conexion)); ?>;

    // Caseta
    const ctxCaseta = document.getElementById("eliminacionesCaseta").getContext("2d");
    new Chart(ctxCaseta, {
        type: 'bar',
        data: {
            labels: Object.keys(datosCaseta),
            datasets: [{
                label: 'Eliminaciones por Caseta',
                data: Object.values(datosCaseta),
                backgroundColor: '#4caf50'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Eliminaciones por Caseta'
                }
            }
        }
    });

    // Fechas
    const ctxFecha = document.getElementById("eliminacionesFecha").getContext("2d");
    new Chart(ctxFecha, {
        type: 'line',
        data: {
            labels: Object.keys(datosFecha),
            datasets: [{
                label: 'Total Eliminaciones',
                data: Object.values(datosFecha),
                borderColor: '#2196f3',
                fill: false,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Eliminaciones a lo Largo del Tiempo'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Fecha'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Cantidad'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    const datosCausas = <?= json_encode(getCausasDeMuerte($conexion)); ?>;

    const ctxCausas = document.getElementById("causasMuerte").getContext("2d");
    new Chart(ctxCausas, {
        type: 'pie',
        data: {
            labels: Object.keys(datosCausas),
            datasets: [{
                label: 'Causas de Muerte',
                data: Object.values(datosCausas),
                backgroundColor: [
                    '#ff6384', '#36a2eb', '#ffcd56', '#4caf50', '#9575cd', '#ff7043', '#26c6da'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribución de Causas de Muerte'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
<script>
    async function generarPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        let y = 10;

        // Mostrar las gráficas ocultas
        const graficasDiv = document.getElementById("graficas");
        graficasDiv.classList.remove("hidden");

        // Esperar pequeño tiempo para renderizado
        await new Promise(resolve => setTimeout(resolve, 300));

        // ====== ENCABEZADO ======
        doc.setFontSize(22);
        doc.text("GestAP", 105, y, { align: "center" });
        y += 12;

        doc.setFontSize(14);
        doc.text("Reporte de Eliminaciones de Cerdos", 105, y, { align: "center" });
        y += 10;

        // Fecha actual y campo de granja
        const fecha = new Date();
        const fechaStr = fecha.toLocaleString();

        doc.setFontSize(11);
        doc.text("Fecha de generación: " + fechaStr, 10, y);
        y += 7;

        doc.text("Granja:", 10, y); // Dejar en blanco para completar a mano
        y += 10;

        // ====== TABLA: Eliminaciones por Venta ======
        doc.setFontSize(13);
        doc.text("➡ Eliminaciones por Venta", 10, y);
        y += 8;

        <?php
        $result_ventas->data_seek(0);
        while ($fila = $result_ventas->fetch_assoc()) {
            $linea = "Fecha: {$fila['fecha_venta']} | Caseta: {$fila['num_caseta']} | Corral: {$fila['num_corral']} | Cantidad: {$fila['cantidad']}";
            echo "doc.text('". addslashes($linea) ."', 10, y); y += 8;\n";
        }
        ?>

        y += 10;
        if (y > 250) { doc.addPage(); y = 10; }

        // ====== TABLA: Eliminaciones por Muerte ======
        doc.setFontSize(13);
        doc.text("➡ Eliminaciones por Muerte", 10, y);
        y += 8;

        <?php
        $result_muertes->data_seek(0);
        while ($fila = $result_muertes->fetch_assoc()) {
            $linea = "Fecha: {$fila['fecha_muerte']} | Caseta: {$fila['num_caseta']} | Corral: {$fila['num_corral']} | Causa: {$fila['causa_muerte']}";
            echo "doc.text('". addslashes($linea) ."', 10, y); y += 8;\n";
        }
        ?>

        // ====== FUNCIÓN PARA INSERTAR GRÁFICAS ======
        async function agregarGrafica(doc, canvasId, titulo, y) {
            if (y > 220) {
                doc.addPage();
                y = 10;
            }

            doc.setFontSize(13);
            doc.text(titulo, 10, y);
            y += 5;

            const canvas = document.getElementById(canvasId);
            const canvasImage = await html2canvas(canvas);
            const imgData = canvasImage.toDataURL("image/png");
            const imgWidth = 180;
            const imgHeight = canvasImage.height * imgWidth / canvasImage.width;
            doc.addImage(imgData, 'PNG', 10, y, imgWidth, imgHeight);

            return y + imgHeight + 10;
        }

        // ====== AGREGAR GRÁFICAS ======
        y = await agregarGrafica(doc, "eliminacionesCaseta", " Eliminaciones por Caseta", y);
        y = await agregarGrafica(doc, "eliminacionesFecha", " Eliminaciones en el Tiempo", y);
        y = await agregarGrafica(doc, "causasMuerte", " Causas de Muerte", y);

        // Ocultar gráficas nuevamente
        graficasDiv.classList.add("hidden");

        // ====== GUARDAR PDF ======
        doc.save("Reporte_Eliminaciones.pdf");
    }
</script>





</body>
</html>
