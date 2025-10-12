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

// --- Handler AJAX para filtrar por rango de fechas ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'filter') {
    header('Content-Type: application/json; charset=utf-8');

    // Obtener y validar fechas (YYYY-MM-DD)
    $start = isset($_POST['start']) && $_POST['start'] !== '' ? $_POST['start'] : null;
    $end = isset($_POST['end']) && $_POST['end'] !== '' ? $_POST['end'] : null;
    $validDate = function($d) { return preg_match('/^\d{4}-\d{2}-\d{2}$/', $d); };

    $ventaWhere = '';
    $muerteWhere = '';

    if ($start && $validDate($start)) {
        $s = $conexion->real_escape_string($start);
        $ventaWhere .= " AND fecha_venta >= '$s'";
        $muerteWhere .= " AND fecha_muerte >= '$s'";
    }
    if ($end && $validDate($end)) {
        $e = $conexion->real_escape_string($end);
        // Si las columnas son DATETIME y quieres incluir todo el día final, cambia a 'YYYY-MM-DD 23:59:59'
        $ventaWhere .= " AND fecha_venta <= '$e'";
        $muerteWhere .= " AND fecha_muerte <= '$e'";
    }

    // 1) Ventas filtradas
    $ventas = [];
    $sqlVentas = "SELECT fecha_venta, cantidad, num_caseta, num_corral FROM eliminacion_venta WHERE 1=1 $ventaWhere ORDER BY fecha_venta DESC";
    $res = $conexion->query($sqlVentas);
    if ($res) {
        while ($r = $res->fetch_assoc()) $ventas[] = $r;
    }

    // 2) Muertes filtradas
    $muertes = [];
    $sqlMuertes = "SELECT fecha_muerte, num_caseta, num_corral, causa_muerte FROM eliminacion_muerte WHERE 1=1 $muerteWhere ORDER BY fecha_muerte DESC";
    $res = $conexion->query($sqlMuertes);
    if ($res) {
        while ($r = $res->fetch_assoc()) $muertes[] = $r;
    }

    // 3) Agregados por caseta
    $caseta = [];
    $sqlCasetaVenta = "SELECT num_caseta, SUM(cantidad) as total FROM eliminacion_venta WHERE 1=1 $ventaWhere GROUP BY num_caseta";
    $res = $conexion->query($sqlCasetaVenta);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $key = 'Caseta ' . $r['num_caseta'];
            $caseta[$key] = ($caseta[$key] ?? 0) + (int)$r['total'];
        }
    }
    $sqlCasetaMuerte = "SELECT num_caseta, COUNT(*) as total FROM eliminacion_muerte WHERE 1=1 $muerteWhere GROUP BY num_caseta";
    $res = $conexion->query($sqlCasetaMuerte);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $key = 'Caseta ' . $r['num_caseta'];
            $caseta[$key] = ($caseta[$key] ?? 0) + (int)$r['total'];
        }
    }

    // 4) Agregados por fecha
    $porFecha = [];
    $sqlFechaVentas = "SELECT fecha_venta AS fecha, SUM(cantidad) as total FROM eliminacion_venta WHERE 1=1 $ventaWhere GROUP BY fecha_venta";
    $res = $conexion->query($sqlFechaVentas);
    if ($res) { while ($r = $res->fetch_assoc()) $porFecha[$r['fecha']] = ($porFecha[$r['fecha']] ?? 0) + (int)$r['total']; }
    $sqlFechaMuertes = "SELECT fecha_muerte AS fecha, COUNT(*) as total FROM eliminacion_muerte WHERE 1=1 $muerteWhere GROUP BY fecha_muerte";
    $res = $conexion->query($sqlFechaMuertes);
    if ($res) { while ($r = $res->fetch_assoc()) $porFecha[$r['fecha']] = ($porFecha[$r['fecha']] ?? 0) + (int)$r['total']; }
    ksort($porFecha);

    // 5) Causas de muerte
    $causas = [];
    $sqlCausas = "SELECT causa_muerte, COUNT(*) as total FROM eliminacion_muerte WHERE 1=1 $muerteWhere GROUP BY causa_muerte";
    $res = $conexion->query($sqlCausas);
    if ($res) { while ($r = $res->fetch_assoc()) $causas[$r['causa_muerte']] = (int)$r['total']; }

    echo json_encode([
        'success' => true,
        'ventas' => $ventas,
        'muertes' => $muertes,
        'caseta' => $caseta,
        'porFecha' => $porFecha,
        'causas' => $causas,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// --- Funciones originales para generación de datos por defecto (cuando no se filtra) ---
function getEliminacionesPorCaseta($conexion) {
    $data = [];
    $query = "SELECT num_caseta, SUM(cantidad) as total FROM eliminacion_venta GROUP BY num_caseta";
    $result = $conexion->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) $data['Caseta ' . $row['num_caseta']] = (int)$row['total'];
    }
    $query2 = "SELECT num_caseta, COUNT(*) as total FROM eliminacion_muerte GROUP BY num_caseta";
    $result2 = $conexion->query($query2);
    if ($result2) {
        while ($row = $result2->fetch_assoc()) {
            $key = 'Caseta ' . $row['num_caseta'];
            $data[$key] = ($data[$key] ?? 0) + (int)$row['total'];
        }
    }
    return $data;
}

function getEliminacionesPorFecha($conexion) {
    $data = [];
    $query = "SELECT fecha_venta AS fecha, SUM(cantidad) as total FROM eliminacion_venta GROUP BY fecha_venta UNION ALL SELECT fecha_muerte AS fecha, COUNT(*) as total FROM eliminacion_muerte GROUP BY fecha_muerte";
    $result = $conexion->query($query);
    if ($result) { while ($row = $result->fetch_assoc()) $data[$row['fecha']] = ($data[$row['fecha']] ?? 0) + (int)$row['total']; }
    ksort($data);
    return $data;
}

function getCausasDeMuerte($conexion) {
    $data = [];
    $query = "SELECT causa_muerte, COUNT(*) as total FROM eliminacion_muerte GROUP BY causa_muerte";
    $result = $conexion->query($query);
    if ($result) { while ($row = $result->fetch_assoc()) $data[$row['causa_muerte']] = (int)$row['total']; }
    return $data;
}

// Consultas por defecto (se usan para mostrar tablas inicialmente)
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

    <!-- Librerías -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <!-- Estilos -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_reportes.css">

    <script src="js/bootstrap.bundle.min.js"></script>

    <style>
        .hidden { display: none !important; }
        .grafica { max-width: 100%; height: 320px; }
        table.table { width: 100%; }
        .controls-centered { display: flex; justify-content: center; gap: 15px; margin-bottom: 20px; margin-top: 10px; flex-direction: column; }
        .controls-row { display:flex; gap:12px; align-items:center; }
        .tooltip-inner { background-color: black !important; color: white !important; font-weight: bold; }
        .tooltip.bs-tooltip-top .tooltip-arrow::before { border-top-color: black !important; }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop();
            sidebarLinks.forEach(link => { link.classList.remove("active"); if (link.getAttribute("href") === currentPath) link.classList.add("active"); });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl); });
        });

        function toggleSection(id) { document.getElementById(id).classList.toggle('hidden'); }
    </script>
</head>
<body>

<div class="navbar d-flex justify-content-between align-items-center px-4 py-2 bg-light shadow">
    <h1 class="mb-0">GestAP</h1>
    <div class="d-flex align-items-center">
        <i class="fas fa-user-circle me-2"></i>
        <span><?= htmlspecialchars($nombre ?? 'Usuario') ?></span>
    </div>
</div>

<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container py-3">
        <h2>Reportes de Eliminación</h2>

        <!-- Controles: rango de fecha + botones -->
        <div class="controls-centered">
            <div style="display:flex; justify-content:center; gap:15px;">
                <button class="btn btn-primary rounded-circle shadow" style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;" onclick="toggleSection('tablaVentas')" data-bs-title="Ver Tabla de Ventas">
                    <i class="fa-solid fa-dollar-sign"></i>
                </button>

                <button class="btn btn-danger rounded-circle shadow" style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;" onclick="toggleSection('tablaMuertes')" data-bs-title="Ver Tabla de Muertes">
                    <i class="fa-solid fa-skull"></i>
                </button>

                <button class="btn btn-info rounded-circle shadow" style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;" onclick="toggleSection('graficas')" data-bs-title="Ver Gráficos">
                    <i class="fa-solid fa-chart-column"></i>
                </button>

                <button id="downloadPdfBtn" class="btn btn-warning rounded-circle shadow" style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;" data-bs-title="Descargar Reporte PDF">
                    <i class="fa-solid fa-arrow-down"></i>
                </button>
                
                <div class="controls-row">
                <label for="startDate" class="mb-0">Desde</label>
                <input id="startDate" type="date" class="form-control" style="width:150px;" />
                <label for="endDate" class="mb-0">Hasta</label>
                <input id="endDate" type="date" class="form-control" style="width:150px;" />
                <button id="filtrarBtn" class="btn btn-secondary" type="button">Aplicar rango</button>
                </div>
            </div>
        </div>

        <!-- Tabla de Ventas -->
        <div id="tablaVentas" class="hidden">
            <h3>Eliminaciones por Venta</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr><th>Fecha</th><th>Caseta</th><th>Corral</th><th>Cantidad</th></tr>
                </thead>
                <tbody>
                    <?php if ($result_ventas && $result_ventas->num_rows > 0): $result_ventas->data_seek(0); while ($fila = $result_ventas->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['fecha_venta']) ?></td>
                            <td><?= htmlspecialchars($fila['num_caseta']) ?></td>
                            <td><?= htmlspecialchars($fila['num_corral']) ?></td>
                            <td><?= htmlspecialchars($fila['cantidad']) ?></td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="4" class="text-center">No hay registros de ventas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabla de Muertes -->
        <div id="tablaMuertes" class="hidden">
            <h3>Eliminaciones por Muerte</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr><th>Fecha</th><th>Caseta</th><th>Corral</th><th>Causa</th></tr>
                </thead>
                <tbody>
                    <?php if ($result_muertes && $result_muertes->num_rows > 0): $result_muertes->data_seek(0); while ($fila = $result_muertes->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['fecha_muerte']) ?></td>
                            <td><?= htmlspecialchars($fila['num_caseta']) ?></td>
                            <td><?= htmlspecialchars($fila['num_corral']) ?></td>
                            <td><?= htmlspecialchars($fila['causa_muerte']) ?></td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="4" class="text-center">No hay registros de muertes.</td></tr>
                    <?php endif; ?>
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
            <canvas id="causasMuerte" class="grafica"></canvas>
        </div>

    </div>
</div>

<!-- Datos iniciales desde PHP -->
<script>
    // Chart instances globales
    let chartCaseta = null, chartFecha = null, chartCausas = null;

    const datosCasetaInit = <?= json_encode(getEliminacionesPorCaseta($conexion)); ?>;
    const datosFechaInit = <?= json_encode(getEliminacionesPorFecha($conexion)); ?>;
    const datosCausasInit = <?= json_encode(getCausasDeMuerte($conexion)); ?>;

    // Inicializar gráficas después de DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Caseta
        const ctxCaseta = document.getElementById('eliminacionesCaseta').getContext('2d');
        chartCaseta = new Chart(ctxCaseta, {
            type: 'bar',
            data: { labels: Object.keys(datosCasetaInit), datasets: [{ label: 'Eliminaciones por Caseta', data: Object.values(datosCasetaInit), backgroundColor: '#4caf50' }] },
            options: { responsive:true, maintainAspectRatio:true, plugins:{ legend:{display:false}, title:{display:true, text:'Eliminaciones por Caseta'} } }
        });

        // Fecha
        const ctxFecha = document.getElementById('eliminacionesFecha').getContext('2d');
        chartFecha = new Chart(ctxFecha, {
            type: 'line',
            data: { labels: Object.keys(datosFechaInit), datasets: [{ label: 'Total Eliminaciones', data: Object.values(datosFechaInit), borderColor: '#2196f3', fill:false, tension:0.1 }] },
            options: { responsive:true, maintainAspectRatio:true, plugins:{ title:{display:true, text:'Eliminaciones a lo Largo del Tiempo'}}, scales:{ x:{ title:{display:true, text:'Fecha'} }, y:{ title:{display:true, text:'Cantidad'}, beginAtZero:true } } }
        });

        // Causas
        const ctxCausas = document.getElementById('causasMuerte').getContext('2d');
        chartCausas = new Chart(ctxCausas, {
            type: 'pie',
            data: { labels: Object.keys(datosCausasInit), datasets: [{ label: 'Causas de Muerte', data: Object.values(datosCausasInit), backgroundColor: ['#ff6384','#36a2eb','#ffcd56','#4caf50','#9575cd','#ff7043','#26c6da'] }] },
            options: { responsive:true, maintainAspectRatio:true, plugins:{ title:{display:true, text:'Distribución de Causas de Muerte'}, legend:{position:'bottom'}, datalabels:{ color:'#000', font:{weight:'bold', size:12}, formatter: (value)=> value } } },
            plugins: [ChartDataLabels]
        });

        // Botones
        document.getElementById('filtrarBtn').addEventListener('click', onAplicarRango);
        document.getElementById('downloadPdfBtn').addEventListener('click', onDownloadPdf);
    });

    // Fetch helper
    async function fetchDatosFiltrados(start, end) {
        const form = new FormData();
        form.append('action', 'filter');
        if (start) form.append('start', start);
        if (end) form.append('end', end);
        const res = await fetch(window.location.pathname, { method: 'POST', body: form });
        return await res.json();
    }

    // Aplicar rango: actualiza tablas y gráficas
    async function onAplicarRango() {
        const start = document.getElementById('startDate').value || null;
        const end = document.getElementById('endDate').value || null;
        try {
            const data = await fetchDatosFiltrados(start, end);
            if (!data.success) throw new Error('No se obtuvieron datos');

            // Actualizar tablas
            const tbodyVentas = document.querySelector('#tablaVentas tbody');
            const tbodyMuertes = document.querySelector('#tablaMuertes tbody');
            tbodyVentas.innerHTML = '';
            tbodyMuertes.innerHTML = '';

            if (data.ventas.length) {
                data.ventas.forEach(v => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${v.fecha_venta}</td><td>${v.num_caseta}</td><td>${v.num_corral}</td><td>${v.cantidad}</td>`;
                    tbodyVentas.appendChild(tr);
                });
            } else {
                tbodyVentas.innerHTML = `<tr><td colspan="4" class="text-center">No hay registros de ventas en el rango.</td></tr>`;
            }

            if (data.muertes.length) {
                data.muertes.forEach(m => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${m.fecha_muerte}</td><td>${m.num_caseta}</td><td>${m.num_corral}</td><td>${m.causa_muerte}</td>`;
                    tbodyMuertes.appendChild(tr);
                });
            } else {
                tbodyMuertes.innerHTML = `<tr><td colspan="4" class="text-center">No hay registros de muertes en el rango.</td></tr>`;
            }

            // Actualizar gráficas
            const datosCaseta = data.caseta;
            const datosFecha = data.porFecha;
            const datosCausas = data.causas;

            chartCaseta.data.labels = Object.keys(datosCaseta);
            chartCaseta.data.datasets[0].data = Object.values(datosCaseta);
            chartCaseta.update();

            chartFecha.data.labels = Object.keys(datosFecha);
            chartFecha.data.datasets[0].data = Object.values(datosFecha);
            chartFecha.update();

            chartCausas.data.labels = Object.keys(datosCausas);
            chartCausas.data.datasets[0].data = Object.values(datosCausas);
            chartCausas.update();

            // Mostrar secciones si estaban ocultas
            document.getElementById('tablaVentas').classList.remove('hidden');
            document.getElementById('tablaMuertes').classList.remove('hidden');
            document.getElementById('graficas').classList.remove('hidden');

        } catch (err) {
            console.error(err);
            alert('Error al obtener datos filtrados. Revisa la consola.');
        }
    }

    // Generar PDF con datos filtrados
    async function onDownloadPdf() {
        const start = document.getElementById('startDate').value || null;
        const end = document.getElementById('endDate').value || null;
        try {
            const data = await fetchDatosFiltrados(start, end);
            if (!data.success) throw new Error('No se obtuvieron datos.');

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            let y = 15;

            doc.setFontSize(18);
            doc.text('Reporte de Eliminaciones', 105, y, { align: 'center' });
            y += 8;
            doc.setFontSize(10);
            const rangoTexto = 'Rango: ' + (start || 'inicio') + ' — ' + (end || 'fin');
            doc.text(rangoTexto, 105, y, { align: 'center' });
            y += 12;

            // Tabla Ventas
            doc.setFontSize(12);
            doc.text('Eliminaciones por Venta', 14, y);
            y += 6;
            const ventasHead = [['Fecha','Caseta','Corral','Cantidad']];
            const ventasBody = data.ventas.map(v => [v.fecha_venta, v.num_caseta, v.num_corral, v.cantidad]);
            doc.autoTable({ startY: y, head: ventasHead, body: ventasBody, margin: { left:10, right:10 } });
            y = doc.lastAutoTable.finalY + 10;

            // Tabla Muertes
            doc.text('Eliminaciones por Muerte', 14, y);
            y += 6;
            const muertesHead = [['Fecha','Caseta','Corral','Causa']];
            const muertesBody = data.muertes.map(m => [m.fecha_muerte, m.num_caseta, m.num_corral, m.causa_muerte]);
            doc.autoTable({ startY: y, head: muertesHead, body: muertesBody, margin: { left:10, right:10 } });
            y = doc.lastAutoTable.finalY + 10;

            // Gráficas: hacer visibles temporalmente
            const graficasDiv = document.getElementById('graficas');
            const originalState = { class: graficasDiv.className, display: graficasDiv.style.display, opacity: graficasDiv.style.opacity };
            graficasDiv.classList.remove('hidden'); graficasDiv.style.display = 'block'; graficasDiv.style.opacity = '1';
            await new Promise(r => setTimeout(r, 600));

            const chartsToAdd = [
                { id: 'eliminacionesCaseta', title: 'Por Caseta', width: 140 },
                { id: 'eliminacionesFecha', title: 'Por Fecha', width: 140 },
                { id: 'causasMuerte', title: 'Causas de Muerte', width: 110 }
            ];

            for (const ch of chartsToAdd) {
                if (y > 200) { doc.addPage(); y = 15; }
                const canvas = document.getElementById(ch.id);
                if (!canvas) continue;
                const canvasImg = await html2canvas(canvas, { scale: 1 });
                const imgData = canvasImg.toDataURL('image/png');
                const imgHeight = (canvas.offsetHeight * ch.width) / canvas.offsetWidth;
                doc.text(ch.title, 14, y); y += 6;
                const xPos = (doc.internal.pageSize.width - ch.width) / 2;
                doc.addImage(imgData, 'PNG', xPos, y, ch.width, imgHeight);
                y += imgHeight + 10;
            }

            // Restaurar estado
            graficasDiv.className = originalState.class; graficasDiv.style.display = originalState.display; graficasDiv.style.opacity = originalState.opacity;

            const filename = `Reporte_Eliminaciones_${(start||'inicio')}_a_${(end||'fin')}.pdf`;
            doc.save(filename);

        } catch (err) {
            console.error(err);
            alert('Error al generar PDF. Revisa la consola.');
        }
    }
</script>

</body>
</html>