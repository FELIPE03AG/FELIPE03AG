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
    <title>Gestión de Alimento</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_cerdos.css">
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

       <!-- Contenedor de botones -->

  <!-- Botones circulares -->
<div class="mb-3 d-flex gap-2">

  <!-- Botón circular para abrir el Modal Agregar -->
  <button class="btn btn-success rounded-circle" 
          style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"
          data-bs-toggle="modal" 
          data-bs-target="#modalAgregar"
          data-bs-placement="top" 
          data-bs-title="Agregar Registro">
      <i class="fas fa-plus"></i>
  </button>

   <button class="btn btn-warning rounded-circle" 
          style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"
          data-bs-toggle="modal" 
          data-bs-target="#modalReporte"
          data-bs-placement="top" 
          data-bs-title="Descargar PDF">
      <i class="fa-solid fa-arrow-down"></i>
  </button>



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
                        <input type="number" name="num_caseta" class="form-control" required>

                        <label class="form-label mt-2">Cantidad de Alimento (Toneladas):</label>
                        <input type="number" step="0.01" name="cantidad" class="form-control" required>

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


<!-- Modal para seleccionar rango de fechas -->
<div class="modal fade" id="modalReporte" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="reporte_alimento.php" method="POST" target="_blank">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Generar Reporte en PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Fecha Inicio:</label>
                    <input type="date" name="fecha_inicio" class="form-control" required>

                    <label class="form-label mt-2">Fecha Fin:</label>
                    <input type="date" name="fecha_fin" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Generar PDF</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>





 <?php


// Consulta a la tabla tolvas
$sql = "SELECT id, fecha, num_caseta, cantidad, etapa 
        FROM tolvas 
        ORDER BY fecha DESC";

$resultado = $conexion->query($sql);
?>

<!-- Tabla de registros -->
<!-- Tabla de registros -->
<div class="mt-4">
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Fecha y Hora</th>
        <th>Número de Caseta</th>
        <th>Cantidad (Toneladas)</th>
        <th>Etapa</th>
        <th>Acción</th>
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
            <td class="text-center">
              <!-- Botón Eliminar en la tabla -->
              <button class="btn btn-danger btn-sm rounded-circle"
                      style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
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
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">No hay registros en la tabla tolvas</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>


</body>

</html>