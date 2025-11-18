<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");

// Obtener fechas del filtro
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin    = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Consulta base
$sql = "SELECT id, num_caseta, fecha, nombre FROM vacunas";

// Aplicar filtro automático
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $sql .= " WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
} elseif (!empty($fecha_inicio)) {
    $sql .= " WHERE fecha >= '$fecha_inicio'";
} elseif (!empty($fecha_fin)) {
    $sql .= " WHERE fecha <= '$fecha_fin'";
}

$sql .= " ORDER BY fecha DESC";

// Ejecutar consulta
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_cerdos.css">

    <title>Gestión de Vacunación</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />

    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        /* Estilos para Tooltips (Asegura visibilidad) */
        .tooltip-inner {
            background-color: black !important;
            color: white !important;
            font-weight: bold;
        }
        .tooltip.bs-tooltip-top .tooltip-arrow::before {
            border-top-color: black !important;
        }
    </style>
</head>

<body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebarLinks = document.querySelectorAll(".sidebar a");
        const currentPath = window.location.pathname.split("/").pop(); 

        const relatedPages = {
            "vacunacion.php": ["vacunacion.php", "vacunas.php"] 
        };

        sidebarLinks.forEach(link => {
            const href = link.getAttribute("href");
            if (relatedPages[href] && relatedPages[href].includes(currentPath)) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    });
</script>

<?php include 'navbar.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="content">
    <h2 class="mb-4">Gestión de Vacunación Porcina</h2>

    <div class="controls-centered">
        <div style="display:flex; justify-content:center; align-items:center; gap:20px; flex-wrap:wrap;">

            <button class="btn btn-success rounded-circle shadow"
                style="width:45px; height:45px; display:flex; align-items:center; justify-content:center;"
                data-bs-toggle="modal"
                data-bs-target="#modalAgregarVacuna"
                data-bs-title="Agregar Registro"> <i class="fas fa-plus"></i>
            </button>

            <button id="btnDescargarPdf"
                class="btn btn-warning rounded-circle shadow"
                style="width:45px; height:45px; display:flex; align-items:center; justify-content:center;"
                data-bs-title="Descargar Reporte PDF">
                <i class="fa-solid fa-arrow-down"></i>
            </button>
            
            <form method="GET" id="formFechas"
                class="controls-row d-flex align-items-center justify-content-center gap-3 flex-wrap bg-light p-3 rounded shadow-sm">
                
                <label for="startDate" class="mb-0 fw-bold">Desde</label>
                <input id="startDate" type="date" name="fecha_inicio" class="form-control" style="width:150px;" 
                        value="<?= $fecha_inicio ?>" /> 

                <label for="endDate" class="mb-0 fw-bold">Hasta</label>
                <input id="endDate" type="date" name="fecha_fin" class="form-control" style="width:150px;" 
                        value="<?= $fecha_fin ?>" />

                </form>
            
            <a href="vacunacion.php" class="btn btn-outline-secondary">
                <i class="fas fa-broom"></i> Limpiar
            </a>
        </div>
    </div>
    
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-title]');
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el, { trigger: 'hover' }));
    });
    </script>

    <div class="modal fade" id="modalAgregarVacuna" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="agregar_vacuna.php" method="POST">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Agregar Vacuna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label>Fecha:</label>
                        <input type="date" name="fecha" class="form-control"
                                value="<?= date('Y-m-d') ?>" readonly>

                        <label class="mt-2">Número de Caseta:</label>
                        <input type="number" name="num_caseta" class="form-control" required min="1" max="6">

                        <label class="mt-2">Nombre de la Vacuna:</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="eliminar_vacuna.php" method="POST">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Eliminar Registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="idEliminar">
                        <p>¿Seguro que deseas eliminar este registro?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Número de Caseta</th>
                <th>Fecha</th>
                <th>Vacuna</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila['num_caseta'] ?></td>
                        <td><?= $fila['fecha'] ?></td>
                        <td><?= $fila['nombre'] ?></td>
                        <td class="d-flex justify-content-center align-items-center" style="height: 50px;">
                            <button class="btn btn-danger btn-sm rounded-circle"
                                    style="width: 35px; height: 35px; display:flex; align-items:center; justify-content:center;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminar"
                                    data-bs-title="Eliminar Registro"
                                    data-id="<?= $fila['id'] ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                 <tr><td colspan="4" class="text-center">No hay registros de vacunación.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

<script>
document.getElementById("startDate").addEventListener("change", () => {
    // Si el formulario existe, envíalo
    const form = document.getElementById("formFechas");
    if (form) form.submit();
});
document.getElementById("endDate").addEventListener("change", () => {
    // Si el formulario existe, envíalo
    const form = document.getElementById("formFechas");
    if (form) form.submit();
});
</script>

<script>
document.getElementById('modalEliminar').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    document.getElementById('idEliminar').value = id;
});
</script>

<script>
async function fetchDatosVacunacion() {
    // Usamos las filas que la consulta PHP ya trajo
    const rows = Array.from(document.querySelectorAll('table tbody tr:not(:has(td[colspan]))'));
    return rows.map(r => ({
        num_caseta: r.cells[0].innerText,
        fecha: r.cells[1].innerText,
        nombre: r.cells[2].innerText
    }));
}

document.getElementById('btnDescargarPdf').addEventListener('click', async () => {
    // Leer valores actuales de los inputs de fecha
    const startInput = document.getElementById('startDate').value;
    const endInput = document.getElementById('endDate').value;

    const start = startInput || 'inicio';
    const end   = endInput || 'fin';

    const data = await fetchDatosVacunacion();
    if (data.length === 0) {
        alert("No hay datos para exportar.");
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(18);
    doc.text("Reporte de Vacunación", 105, 15, { align: "center" });

    doc.setFontSize(10);
    doc.text(`Rango: ${start} - ${end}`, 105, 25, { align: "center" });

    doc.autoTable({
        startY: 35,
        head: [['Caseta', 'Fecha', 'Vacuna']],
        body: data.map(v => [v.num_caseta, v.fecha, v.nombre]),
        styles: { fontSize: 10 }
    });

    doc.save("reporte_vacunacion.pdf");
});
</script>

</body>
</html>