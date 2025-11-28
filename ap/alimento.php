<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

// Incluye la conexión a la base de datos
include("config.php");

// Consulta a la tabla tolvas para obtener todos los registros ordenados por fecha descendente
$sql = "SELECT id, fecha, num_caseta, cantidad, etapa 
        FROM tolvas 
        ORDER BY fecha DESC";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Gestión de Alimento</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_cerdos.css">

    <style>
    /* Ocultar flechas de control en input type="number" */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Estilos para Tooltips */
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); 
            const relatedPages = {
                "alimento.php": ["alimento.php", "alim.php"] 
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
    <h2 class="mb-4">Gestión de Tolvas de Alimento</h2>

    <div class="controls-centered">
        <div style="display:flex; justify-content:center; align-items:center; gap:20px; flex-wrap:wrap;">

            <button class="btn btn-success rounded-circle shadow"
                style="width:45px; height:45px; display:flex; align-items:center; justify-content:center;"
                data-bs-toggle="modal"
                data-bs-target="#modalAgregar"
                data-bs-title="Agregar Registro">
                <i class="fas fa-plus"></i>
            </button>

            <button id="btnDescargarPdf" class="btn btn-warning rounded-circle shadow"
                style="width:45px; height:45px; display:flex; align-items:center; justify-content:center;"
                data-bs-title="Descargar Reporte PDF">
                <i class="fa-solid fa-arrow-down"></i>
            </button>

            <div class="controls-row d-flex align-items-center justify-content-center gap-3 flex-wrap bg-light p-3 rounded shadow-sm">
                <label for="startDate" class="mb-0 fw-bold">Desde</label>
                <input id="startDate" type="date" class="form-control" style="width:150px;" />

                <label for="endDate" class="mb-0 fw-bold">Hasta</label>
                <input id="endDate" type="date" class="form-control" style="width:150px;" />
            </div>
            <button id="limpiarFiltroBtn" class="btn btn-outline-secondary">
                    <i class="fas fa-broom"></i> Limpiar
            </button>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-title]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
});
</script>

<div class="modal fade" id="modalAgregar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="agregar_alimento.php" method="POST">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Agregar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Fecha:</label>
                    <input type="date" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>

                    <label class="form-label mt-2">Número de Caseta:</label>
                    <input type="number" name="num_caseta" class="form-control" required min="1" max="6">

                    <label class="form-label mt-2">Cantidad (Toneladas):</label>
                    <input type="number" step="0.01" name="cantidad" class="form-control" required min="0" max="5">

                    <label class="form-label mt-2">Etapa:</label>
                    <select name="etapa" class="form-select" required>
                        <option>Iniciador</option>
                        <option>Crecimiento</option>
                        <option>Desarrollo</option>
                        <option>Finalizador</option>
                    </select>
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
            <form action="eliminar_alimento.php" method="POST">
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

<script>

// Pasar ID al modal de eliminar
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById('modalEliminar');
    modal.addEventListener('show.bs.modal', e => {
        const id = e.relatedTarget.getAttribute('data-id');
        modal.querySelector('#idEliminar').value = id;
    });
});
</script>

   <table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Fecha</th>
            <th>Caseta</th>
            <th>Cantidad</th>
            <th>Etapa</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php if($resultado && $resultado->num_rows > 0): ?>
            <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td data-fecha="<?= $fila['fecha'] ?>">
                        <?= date("d/m/Y", strtotime($fila['fecha'])) ?>
                    </td>
                    <td><?= $fila['num_caseta'] ?></td>
                    <td><?= $fila['cantidad'] ?></td>
                    <td><?= $fila['etapa'] ?></td>

                    <td class="d-flex justify-content-center align-items-center" style="height: 50px;">
                        <button class="btn btn-danger btn-sm rounded-circle"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEliminar"
                            data-id="<?= $fila['id'] ?>"
                            data-bs-title="Eliminar Registro"
                            style="width: 35px; height: 35px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">No hay registros</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
document.addEventListener("DOMContentLoaded", () => {

    function aplicarFiltro() {
    const start = document.getElementById("startDate").value;
    const end = document.getElementById("endDate").value;

    const rows = document.querySelectorAll("table tbody tr:not(#emptyMsg)");
    let visibleCount = 0;
    const tbody = document.querySelector("table tbody");
    let msg = document.getElementById("emptyMsg");

    rows.forEach(r => {
        const rowDate = r.cells[0].dataset.fecha; // FECHA REAL
        let show = true;

        if (start && rowDate < start) show = false;
        if (end && rowDate > end) show = false;

        r.style.display = show ? "" : "none";
        if (show) visibleCount++;
    });

    if (visibleCount === 0) {
        if (!msg) {
            msg = document.createElement("tr");
            msg.id = "emptyMsg";
            msg.innerHTML = `<td colspan="5" class="text-center">No hay registros en ese rango</td>`;
            tbody.appendChild(msg);
        }
    } else if (msg) {
        msg.remove();
    }
}


    // --- Event Listeners ---
    document.getElementById("startDate").addEventListener("change", aplicarFiltro);
    document.getElementById("endDate").addEventListener("change", aplicarFiltro);

    document.getElementById("limpiarFiltroBtn").addEventListener("click", () => {
        document.getElementById("startDate").value = "";
        document.getElementById("endDate").value = "";
        aplicarFiltro(); // Aplica el filtro para mostrar todo
    });

    // Asegura que se aplica el filtro inicial (en caso de recarga con valores)
    aplicarFiltro();
});
</script>

<script>
document.getElementById("btnDescargarPdf").addEventListener("click", () => {
    
    // Leer valores actuales de los inputs de fecha
    const startInput = document.getElementById('startDate').value;
    const endInput = document.getElementById('endDate').value;

    // Asignar 'inicio' o 'fin' si el campo está vacío
    const start = startInput || 'inicio';
    const end   = endInput || 'fin';

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    let y = 15;

    doc.setFontSize(18);
    // Título del Reporte
    doc.text("Reporte de Alimento", 105, y, { align: "center" });

    y += 10;
    
    // AÑADIDO: Rango de fechas
    doc.setFontSize(10);
    doc.text(`Rango: ${start} - ${end}`, 105, y, { align: "center" });

    y += 10; // Incrementamos la posición Y para empezar la tabla debajo del rango

    // Solo selecciona las filas VISIBLES y excluye el mensaje de vacío
    const rows = [...document.querySelectorAll("table tbody tr")].filter(r => 
        r.style.display !== "none" && r.id !== "emptyMsg"
    );

    const data = rows.map(r => [
        r.cells[0].innerText, // Fecha
        r.cells[1].innerText, // Caseta
        r.cells[2].innerText, // Cantidad
        r.cells[3].innerText  // Etapa
    ]);
    
    // Alerta si no hay datos visibles para descargar
    if (data.length === 0) {
        alert("No hay datos visibles para descargar en el reporte PDF.");
        return;
    }


    doc.autoTable({
        head: [["Fecha","Caseta","Cantidad (Ton.)","Etapa"]],
        body: data,
        // Usamos startY para empezar la tabla después del título y el rango
        startY: y, 
        styles: { fontSize: 10 } // Añadido para igualar el tamaño de fuente al ejemplo
    });

    doc.save("reporte_alimento.pdf");
});
</script>

<?php if (isset($_GET['success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Registro guardado!',
    text: 'La información fue almacenada correctamente.',
    showConfirmButton: false,
    timer: 2000
}).then(() => {
    window.location.href = "alimento.php";
});
</script>
<?php endif; ?>

</body>
</html>