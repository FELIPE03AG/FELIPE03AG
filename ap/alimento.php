<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");

// Consulta a la tabla tolvas
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
    <script src="js/snippets.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <title>Gestión de Alimento</title>
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
                "alimento.php": ["alimento.php", "alim.php"] // Páginas relacionadas con "cerdos"
                
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
        <h2 class="mb-4">Gestión de Tolvas de Alimento</h2>

<div class="controls-centered">
  <div style="display:flex; justify-content:center; align-items:center; gap:20px; flex-wrap:wrap;">
      <!-- Botón circular para abrir el Modal Agregar -->
      <button class="btn btn-success rounded-circle shadow" 
              style="width:55px; height:55px; display:flex; align-items:center; justify-content:center;"
              data-bs-toggle="modal" 
              data-bs-target="#modalAgregar"
              data-bs-title="Agregar Registro">
          <i class="fas fa-plus"></i>
      </button>

      <!-- Botón circular para descargar reporte PDF -->
      <button id="btnDescargarPdf" class="btn btn-warning rounded-circle shadow" 
              style="width:55px; height:55px; display:flex; align-items:center; justify-content:center;"
              data-bs-toggle="modal" 
              data-bs-target="#modalReporte"
              data-bs-title="Descargar Reporte PDF">
          <i class="fa-solid fa-arrow-down"></i>
      </button>

      <!-- Filtro de rango de fechas -->
        <div class="controls-row d-flex align-items-center justify-content-center gap-3 flex-wrap bg-light p-3 rounded shadow-sm">
            <label for="startDate" class="mb-0 fw-bold">Desde</label>
            <input id="startDate" type="date" class="form-control" style="width:150px;" />
            <label for="endDate" class="mb-0 fw-bold">Hasta</label>
            <input id="endDate" type="date" class="form-control" style="width:150px;" />
        </div>
  </div>
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



 <!-- Modal Agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
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

                        <label class="form-label mt-2">Cantidad de Alimento (Toneladas):</label>
                        <input type="number" step="0.01" name="cantidad" class="form-control" required min="0" max="5">


                        <label class="form-label mt-2">Etapa:</label>
                        <select name="etapa" class="form-select" required>
                            <option value="Iniciador">Iniciador</option>
                            <option value="Crecimiento">Crecimiento</option>
                            <option value="Desarrollo">Desarrollo</option>
                            <option value="Finalizador">Finalizador</option>
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

    
    <!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
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

<!-- Tabla de registros -->
<div class="mt-4">
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Fecha y Hora</th>
        <th>Número de Caseta</th>
        <th>Cantidad (Toneladas)</th>
        <th>Etapa</th>
        <th>Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <?php if($resultado && $resultado->num_rows > 0): ?>
        <?php while($fila = $resultado->fetch_assoc()): ?>
          <tr>
            <td><?= $fila['fecha'] ?></td>
            <td><?= $fila['num_caseta'] ?></td>
            <td><?= $fila['cantidad'] ?></td>
            <td><?= $fila['etapa'] ?></td>
            <td class="text-center align-middle">
            <div class="d-flex justify-content-center">
                <button class="btn btn-danger btn-sm rounded-circle"
                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                        data-bs-toggle="modal" 
                        data-bs-target="#modalEliminar"
                        data-id="<?= $fila['id'] ?>"
                        data-bs-placement="top"
                        data-bs-title="Eliminar Registro">
                <i class="fas fa-trash"></i>
                </button>
            </div>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">No hay registros en la tabla tolvas</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  // Helper: convierte texto de celda a objeto Date (saneando hora si existe)
  function parseCellDate(text) {
    if (!text) return null;
    // Si viene con espacio (fecha + hora), tomar solo la parte de fecha
    text = text.toString().trim();
    // Detecta formatos comunes: YYYY-MM-DD o YYYY-MM-DD HH:MM:SS
    // Tomamos la parte de fecha antes del espacio
    let datePart = text.split(' ')[0];
    // Si el formato es YYYY-MM-DD lo convertimos a Date (UTC local)
    const parts = datePart.split('-');
    if (parts.length === 3) {
      // new Date(year, monthIndex, day)
      const y = parseInt(parts[0], 10);
      const m = parseInt(parts[1], 10) - 1;
      const d = parseInt(parts[2], 10);
      if (!isNaN(y) && !isNaN(m) && !isNaN(d)) return new Date(y, m, d);
    }
    // Fallback: intentar Date constructor
    const dt = new Date(text);
    return isNaN(dt.getTime()) ? null : dt;
  }

  function aplicarFiltro() {
  const startVal = document.getElementById('startDate').value;
  const endVal = document.getElementById('endDate').value;
  const filtrar = !!(startVal || endVal);

  const startDate = startVal ? (function(){ const p = startVal.split('-'); return new Date(parseInt(p[0]), parseInt(p[1])-1, parseInt(p[2])); })() : null;
  const endDate = endVal ? (function(){ const p = endVal.split('-'); return new Date(parseInt(p[0]), parseInt(p[1])-1, parseInt(p[2])); })() : null;
  if (endDate) endDate.setHours(23,59,59,999);

  const tbody = document.querySelector('table.table tbody');
  if (!tbody) return;
  const rows = Array.from(tbody.querySelectorAll('tr'));

  let anyVisible = false;
  rows.forEach(row => {
    const cell = row.cells[0];
    if (!cell) {
      row.style.display = '';
      return;
    }
    const cellDate = parseCellDate(cell.textContent || cell.innerText);

    let mostrar = true;
    if (filtrar) {
      if (!cellDate) mostrar = false;
      else {
        if (startDate && cellDate < startDate) mostrar = false;
        if (endDate && cellDate > endDate) mostrar = false;
      }
    } else {
      mostrar = true;
    }

    row.style.display = mostrar ? '' : 'none';
    if (mostrar) anyVisible = true;
  });

  // Mensaje si no hay coincidencias (tu lógica DOM)
  const emptyMsgSelector = '#filtro-empty-row';
  let emptyRow = document.querySelector(emptyMsgSelector);
  if (!anyVisible) {
    if (!emptyRow) {
      emptyRow = document.createElement('tr');
      emptyRow.id = emptyMsgSelector.substring(1);
      emptyRow.innerHTML = '<td colspan="5" class="text-center">No hay registros en el rango seleccionado</td>';
      tbody.appendChild(emptyRow);
    }
  } else {
    if (emptyRow) emptyRow.remove();
  }

  // --- sincronizar con DataTables ---
  // Asegurarnos que DataTables ya está inicializado y obtener la instancia
  let tablaApi = null;
  if ($.fn.dataTable && $.fn.dataTable.isDataTable('table.table')) {
    tablaApi = $('table.table').DataTable();
  }

  if (tablaApi) {
    if (filtrar) {
      // Si quieres mostrar **todos** los resultados cuando hay filtro:
      tablaApi.page.len(-1).draw(false); // -1 = all
      $('.dataTables_paginate').hide();

      // Si en lugar de "mostrar todos" prefieres que vuelva a la primera página:
      // tablaApi.page(0).draw(false);
      // $('.dataTables_paginate').show();

    } else {
      // Restaurar paginación normal (6 por página en tu caso) y llevar a la primera página
      tablaApi.page.len(6).draw(false);
      tablaApi.page(0).draw(false);
      $('.dataTables_paginate').show();
    }
  }
}
  // Eventos: botón aplicar
  const btnFiltrar = document.getElementById('filtrarBtn');
  if (btnFiltrar) {
    btnFiltrar.addEventListener('click', function () {
      aplicarFiltro();
    });
  }

  // Filtrar en cambio de fecha automáticamente (opcional)
  const startInput = document.getElementById('startDate');
  const endInput = document.getElementById('endDate');
  if (startInput) startInput.addEventListener('change', aplicarFiltro);
  if (endInput) endInput.addEventListener('change', aplicarFiltro);

  // Añadir botón "Limpiar filtro" al lado del aplicar (si no existe)
  if (!document.getElementById('limpiarFiltroBtn')) {
    const contenedor = document.querySelector('.controls-row');
    if (contenedor) {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.id = 'limpiarFiltroBtn';
      btn.className = 'btn btn-outline-secondary';
      btn.style.marginLeft = '6px';
      btn.innerHTML = '<i class="fas fa-broom"></i> Limpiar';
      btn.addEventListener('click', function() {
        if (startInput) startInput.value = '';
        if (endInput) endInput.value = '';
        aplicarFiltro();
      });
      contenedor.appendChild(btn);
    }
  }

  // Opcional: aplicar filtro si ya vienen fechas cargadas (por ejemplo después de una recarga)
  aplicarFiltro();
});
</script>

<script>
async function fetchDatosFiltrados(start, end) {
    // Llamada al servidor para obtener datos filtrados
    // Devuelve un objeto con {success: true, ventas: [...]}
    // Por ahora tomamos datos de la tabla HTML directamente
    const rows = Array.from(document.querySelectorAll('table tbody tr'))
        .filter(r => r.style.display !== 'none' && !r.id.includes('filtro-empty-row'));

    const ventas = rows.map(r => {
        const cells = r.cells;
        return {
            fecha_venta: cells[0].innerText,
            num_caseta: cells[3].innerText,
            num_corral: cells[1].innerText,
            cantidad: cells[2].innerText
        };
    });

    return { success: true, ventas };
}

document.getElementById('btnDescargarPdf').addEventListener('click', async function() {
    const start = document.getElementById('startDate').value || null;
    const end = document.getElementById('endDate').value || null;

    try {
        const data = await fetchDatosFiltrados(start, end);
        if (!data.success) throw new Error('No se obtuvieron datos.');

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        let y = 15;

        doc.setFontSize(18);
        doc.text('Reporte Alimento', 105, y, { align: 'center' });
        y += 8;
        doc.setFontSize(10);
        const rangoTexto = 'Rango: ' + (start || 'inicio') + ' — ' + (end || 'fin');
        doc.text(rangoTexto, 105, y, { align: 'center' });
        y += 12;

        // Tabla Ventas
        doc.setFontSize(12);
        doc.text('Registros', 14, y);
        y += 6;

        const ventasHead = [['Fecha','Caseta','Corral','Cantidad']];
        const ventasBody = data.ventas.map(v => [v.fecha_venta, v.num_caseta, v.num_corral, v.cantidad]);

        doc.autoTable({ startY: y, head: ventasHead, body: ventasBody, margin: { left:10, right:10 } });

        // Descargar PDF
        doc.save('reporte_alimento.pdf');

    } catch (err) {
        alert('Error al generar PDF: ' + err.message);
        console.error(err);
    }
});
</script>

<!-- Script DataTables -->
<script>
$(document).ready(function() {
    $('table.table').DataTable({
        "paging": true,         // Activar paginación
        "pageLength": 6,        // Cantidad de registros por página
        "lengthChange": false,  // No permitir cambiar número de registros por página
        "searching": false,     // Desactivar búsqueda
        "ordering": false,      // Desactivar ordenamiento
        "info": false,          // Desactivar info de registros
        "autoWidth": false,
        "language": {
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente"
            },
            "emptyTable": "No hay datos disponibles en la tabla"
        }
    });
});
</script>

<style>
/* Botón activo (página actual) */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #4caf50 !important;
    color: white !important;
    border: 1px solid #4caf50 !important;
    border-radius: 6px !important;
}

/* Hover sobre botones */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #45a049 !important;
    color: white !important;
    border: 1px solid #45a049 !important;
}

/* Quitar color gris predeterminado */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #333 !important;
    border-radius: 6px !important;
}
</style>



</body>

</html>