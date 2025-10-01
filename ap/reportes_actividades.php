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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_reportes.css">

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

        <!-- Usuario sin dropdown -->
        <div class="d-flex align-items-center">
            <i class="fas fa-user-circle me-2"></i>
            <span><?= htmlspecialchars($nombre) ?></span>
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
                <canvas id="eliminacionesCaseta" class="grafica"></canvas>

                <h3>Eliminaciones en el Tiempo</h3>
                <canvas id="eliminacionesFecha" class="grafica"></canvas>

                <h3>Causas de Muerte</h3>
                <canvas id="causasMuerte" class="grafica" ></canvas>

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
            maintainAspectRatio: true,
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
            maintainAspectRatio: true,
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
        maintainAspectRatio: true,
        plugins: {
            title: {
                display: true,
                text: 'Distribución de Causas de Muerte'
            },
            legend: {
                position: 'bottom'
            },
            datalabels: {             // <-- Aquí activamos los números
                color: '#000',
                font: { weight: 'bold', size: 14 },
                formatter: (value, ctx) => value // Muestra el número exacto
            }
        }
    },
    plugins: [ChartDataLabels]
});
</script>
<script>
    async function generarPDF() {
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        let y = 15; // Posición vertical inicial

        // ===== 1. HACER VISIBLES LAS GRÁFICAS TEMPORALMENTE =====
        const graficasDiv = document.getElementById('graficas');
        const originalState = {
            class: graficasDiv.className,
            display: graficasDiv.style.display,
            opacity: graficasDiv.style.opacity
        };
        
        graficasDiv.classList.remove('hidden');
        graficasDiv.style.display = 'block';
        graficasDiv.style.opacity = '1';
        
        // Esperar a que se rendericen los gráficos
        await new Promise(resolve => setTimeout(resolve, 1000));

        // ===== 2. ENCABEZADO =====
        doc.setFontSize(22);
        doc.setTextColor(41, 128, 185);
        doc.text("Reporte de Eliminaciones", 105, y, { align: "center" });
        y += 12;
        
        doc.setFontSize(12);
        doc.setTextColor(100, 100, 100);
        doc.text("Granja Porcícola - " + new Date().toLocaleDateString(), 105, y, { align: "center" });
        y += 20;

        // ===== 3. TABLA DE VENTAS =====
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.text("Eliminaciones por Venta", 14, y);
        y += 8;
        
        const ventasData = [["Fecha", "Caseta", "Corral", "Cantidad"]];
        <?php $result_ventas->data_seek(0); while($fila = $result_ventas->fetch_assoc()): ?>
        ventasData.push(["<?= $fila['fecha_venta'] ?>", "<?= $fila['num_caseta'] ?>", 
                        "<?= $fila['num_corral'] ?>", "<?= $fila['cantidad'] ?>"]);
        <?php endwhile; ?>
        
        doc.autoTable({
            startY: y,
            head: [ventasData[0]],
            body: ventasData.slice(1),
            headStyles: {
                fillColor: [41, 128, 185],
                textColor: 255,
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [240, 240, 240]
            },
            margin: { left: 10, right: 10 }
        });
        
        y = doc.lastAutoTable.finalY + 15;

        // ===== 4. TABLA DE MUERTES =====
        doc.setFontSize(14);
        doc.text("Eliminaciones por Muerte", 14, y);
        y += 8;
        
        const muertesData = [["Fecha", "Caseta", "Corral", "Causa"]];
        <?php $result_muertes->data_seek(0); while($fila = $result_muertes->fetch_assoc()): ?>
        muertesData.push(["<?= $fila['fecha_muerte'] ?>", "<?= $fila['num_caseta'] ?>", 
                         "<?= $fila['num_corral'] ?>", "<?= $fila['causa_muerte'] ?>"]);
        <?php endwhile; ?>
        
        doc.autoTable({
            startY: y,
            head: [muertesData[0]],
            body: muertesData.slice(1),
            headStyles: {
                fillColor: [192, 57, 43],
                textColor: 255,
                fontStyle: 'bold'
            },
            columnStyles: {
                3: { cellWidth: 'auto' }
            },
            margin: { left: 10, right: 10 }
        });
        
        y = doc.lastAutoTable.finalY + 15;

        // ===== 5. GRÁFICAS (TAMAÑO REDUCIDO) =====
        const graficas = [
            { id: 'eliminacionesCaseta', title: 'Eliminaciones por Caseta', width: 120 },
            { id: 'eliminacionesFecha', title: 'Eliminaciones por Fecha', width: 120 },
            { id: 'causasMuerte', title: 'Causas de Muerte', width: 90 }
        ];
        
        for (const grafica of graficas) {
            if (y > 200) { // Si no hay espacio, nueva página
                doc.addPage();
                y = 15;
            }
            
            const canvas = document.getElementById(grafica.id);
            const imgData = await html2canvas(canvas, { scale: 0.8 });
            
            const imgHeight = (canvas.offsetHeight * grafica.width) / canvas.offsetWidth;
            
            doc.setFontSize(12);
            doc.text(grafica.title, 14, y);
            y += 7;
            
            // Centrar gráfica horizontalmente
            const xPos = (doc.internal.pageSize.width - grafica.width) / 2;
            doc.addImage(imgData, 'PNG', xPos, y, grafica.width, imgHeight);
            y += imgHeight + 15;
        }

        // ===== 6. RESTAURAR ESTADO ORIGINAL =====
        graficasDiv.className = originalState.class;
        graficasDiv.style.display = originalState.display;
        graficasDiv.style.opacity = originalState.opacity;

        // ===== 7. GUARDAR PDF =====
        doc.save(`Reporte_Eliminaciones_${new Date().toISOString().slice(0, 10)}.pdf`);
        
    } catch (error) {
        console.error("Error al generar PDF:", error);
        alert("Error al generar el PDF. Ver consola para detalles.");
    }
}
</script>


</body>
</html>