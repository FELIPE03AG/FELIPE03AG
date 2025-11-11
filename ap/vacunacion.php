<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");


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
    <!-- Librerías jsPDF y autoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <title>Gestión de Vacunacion</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_cerdos.css">

     <!-- Estilo para denegar flechas en los cuadros de texto-->
    <style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }
    </style>
</head>

<body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "vacunacion.php": ["vacunacion.php", "vacunas.php"] // Páginas relacionadas con "cerdos"
                
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

        <!-- Usuario sin dropdown -->
        <div class="d-flex align-items-center">
            <i class="fas fa-user-circle me-2"></i>
            <span><?= htmlspecialchars($nombre) ?></span>
        </div>
    </div>


    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>


    <div class="content">
        <h2 class="mb-4">Gestión de Vacunacion Porcina</h2>

  <!-- Contenedor superior: Botones + Filtro -->
<div class="mb-3 d-flex flex-wrap align-items-center justify-content-between bg-light p-3 rounded shadow-sm gap-3">

  <!-- Botones circulares -->
  <div class="d-flex gap-2">
    <!-- Botón Agregar -->
    <button class="btn btn-success rounded-circle"
            style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"
            data-bs-toggle="modal"
            data-bs-target="#modalAgregarVacuna"
            data-bs-placement="top"
            data-bs-title="Agregar Registro">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Botón Reporte -->
    <!-- Botón Reporte PDF -->
    <button id="btnDescargarPdf" 
        class="btn btn-warning rounded-circle"
        style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"
        data-bs-placement="top"
        data-bs-title="Descargar PDF">
    <i class="fa-solid fa-arrow-down"></i>
</button>

  </div>

  <!-- Filtro de rango de fechas -->
  <form method="GET" class="d-flex align-items-center gap-2 flex-wrap mb-0">
      <label for="startDate" class="mb-0 fw-bold">Desde</label>
      <input id="startDate" type="date" name="fecha_inicio" class="form-control" style="width:150px;"
             value="<?= isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '' ?>">

      <label for="endDate" class="mb-0 fw-bold">Hasta</label>
      <input id="endDate" type="date" name="fecha_fin" class="form-control" style="width:150px;"
             value="<?= isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '' ?>">

      <button type="submit" class="btn btn-primary">Filtrar</button>
      <a href="vacunacion.php" class="btn btn-secondary">Limpiar</a>
  </form>
</div>



<!-- Script para inicializar tooltips -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-title]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>

<!-- CSS para personalizar tooltip -->
<style>
  .tooltip-inner {
    background-color: black !important; /* Fondo negro */
    color: white !important;            /* Texto blanco */
    font-weight: bold;
  }
  .tooltip.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: black !important; /* Flecha negra */
  }
</style>


    <!-- Modal Agregar Vacuna -->
<div class="modal fade" id="modalAgregarVacuna" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="agregar_vacuna.php" method="POST">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Agregar Vacuna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Fecha:</label>
                        <input type="date" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>

                    <label class="form-label mt-2">Número de Caseta:</label>
                        <input type="number" name="num_caseta" class="form-control" required min="1" max="6">

                    <label class="form-label mt-2">Nombre de la Vacuna:</label>
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

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
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

<!-- Script para pasar el ID al modal-->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));

    // Capturar id del botón y mandarlo al modal
    const modalEliminar = document.getElementById('modalEliminar');
    modalEliminar.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget; // botón que abrió el modal
        let id = button.getAttribute('data-id'); // obtener el id
        modalEliminar.querySelector('#idEliminar').value = id; // pasarlo al input oculto
    });
});
</script>

<?php
// Obtener las fechas del filtro si existen
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin    = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Consulta base
$sql = "SELECT id, num_caseta, fecha, nombre FROM vacunas";

// Si hay fechas, agregamos el filtro
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $sql .= " WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
} elseif (!empty($fecha_inicio)) {
    $sql .= " WHERE fecha >= '$fecha_inicio'";
} elseif (!empty($fecha_fin)) {
    $sql .= " WHERE fecha <= '$fecha_fin'";
}

// Ordenar los resultados
$sql .= " ORDER BY fecha DESC";

// Ejecutar la consulta
$resultado = $conexion->query($sql);
?>



<!-- Tabla -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Número de Caseta</th>
                <th>Fecha</th>
                <th>Nombre de la Vacuna</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['num_caseta'] ?></td>
                    <td><?= $fila['fecha'] ?></td>
                    <td><?= $fila['nombre'] ?></td>
                    <td class="text-center align-middle">
                    <button class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            style="width: 35px; height: 35px;"
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEliminar"
                            data-id="<?= $fila['id'] ?>"
                            data-bs-placement="top"
                            data-bs-title="Eliminar Registro">
                        <i class="fas fa-trash"></i>
                    </button>
                    </td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script>
async function fetchDatosVacunacion() {
    // Tomar los datos visibles de la tabla
    const rows = Array.from(document.querySelectorAll('table tbody tr'));
    const vacunas = rows.map(r => {
        const cells = r.cells;
        return {
            num_caseta: cells[0].innerText,
            fecha: cells[1].innerText,
            nombre: cells[2].innerText
        };
    });

    return { success: true, vacunas };
}

document.getElementById('btnDescargarPdf').addEventListener('click', async function() {
    const start = document.getElementById('startDate').value || null;
    const end = document.getElementById('endDate').value || null;

    try {
        const data = await fetchDatosVacunacion();
        if (!data.success || data.vacunas.length === 0) {
            alert('No hay registros para generar el PDF.');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        let y = 15;

        // Título principal
        doc.setFontSize(18);
        doc.text('Reporte de Vacunación Porcina', 105, y, { align: 'center' });
        y += 8;

        // Rango de fechas
        doc.setFontSize(10);
        const rangoTexto = 'Rango: ' + (start || 'inicio') + ' — ' + (end || 'fin');
        doc.text(rangoTexto, 105, y, { align: 'center' });
        y += 12;

        // Subtítulo
        doc.setFontSize(12);
        doc.text('Registros:', 14, y);
        y += 6;

        // Encabezados y cuerpo de la tabla
        const head = [['N° Caseta', 'Fecha', 'Vacuna']];
        const body = data.vacunas.map(v => [v.num_caseta, v.fecha, v.nombre]);

        doc.autoTable({
            startY: y,
            head: head,
            body: body,
            styles: { fontSize: 10, cellPadding: 3 },
            headStyles: { fillColor: [40, 167, 69] }, // verde Bootstrap
            margin: { left: 10, right: 10 }
        });

        // Guardar el PDF
        doc.save('reporte_vacunacion.pdf');
    } catch (err) {
        alert('Error al generar PDF: ' + err.message);
        console.error(err);
    }
});
</script>

    </div>
</body>
</html>