<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");

$totalCasetas = 6;

function calcularEtapa($fecha_llegada) {
    $hoy = new DateTime();
    $fecha = new DateTime($fecha_llegada);
    $dias = (int)$hoy->diff($fecha)->format('%a');

    if ($dias <= 30) {
        return "Iniciador";
    } elseif ($dias <= 60) {
        return "Crecimiento";
    } elseif ($dias <= 90) {
        return "Desarrollo";
    } else {
        return "Finalizador";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/snippets.js"></script>
    <link href="styles/style_navbar.css" rel="stylesheet">
    <link href="styles/style_sidebar.css" rel="stylesheet">
    <link href="styles/style_cerdos.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Gestión de Cerdos</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
</head>

<body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarLinks = document.querySelectorAll(".sidebar a");
        const currentPath = window.location.pathname.split("/").pop();

        const relatedPages = {
            "cerdos.php": ["cerdos.php", "add_cerdos.php", "elim_cerdosVenta.php", "elim_cerdosMuerte.php"]
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

<!-- Nav bar -->
<?php include 'navbar.php'; ?>

<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

<div class="content">

    <h2>Gestión de Casetas de Cerdos</h2>
    <div id="contenedor-casetas">

        <?php
        for ($i = 1; $i <= $totalCasetas; $i++) {
            
            $query = "SELECT num_cerdos, peso_promedio, edad_promedio, fecha_llegada, etapa_alimentacion 
                      FROM casetas WHERE id = $i";
            $resultado = $conexion->query($query);

            $cantidad_cerdos = $peso_promedio = $edad_promedio = $fecha_llegada = $etapa_alimentacion = "N/A";

            if ($resultado && $fila = $resultado->fetch_assoc()) {
                $cantidad_cerdos = $fila['num_cerdos'];
                $peso_promedio = $fila['peso_promedio'];
                $edad_promedio = $fila['edad_promedio'];
                $fecha_llegada = $fila['fecha_llegada'];
                $etapa_actual = $fila['etapa_alimentacion'];

                $etapa_calculada = calcularEtapa($fecha_llegada);

                if ($etapa_calculada !== $etapa_actual) {
                    $update_query = "UPDATE casetas SET etapa_alimentacion = '$etapa_calculada' WHERE id = $i";
                    $conexion->query($update_query);
                }

                $etapa_alimentacion = $etapa_calculada;
            }
        ?>

        <div class="caseta">
            <div class="titulo-caseta" onclick="toggleCorrales(<?php echo $i; ?>)">
                Caseta <?php echo $i; ?> <span id="flecha-<?php echo $i; ?>">▼</span>
            </div>

            <div class="atributos">
                <span><strong>Cantidad Inicial:</strong> <?php echo $cantidad_cerdos; ?></span>
                <span><strong>Fecha:</strong> <?php echo $fecha_llegada; ?></span>
                <span><strong>Peso Promedio:</strong> <?php echo $peso_promedio; ?> kg</span>
                <span><strong>Edad Promedio:</strong> <?php echo $edad_promedio; ?> semanas</span>
                <span><strong>Etapa:</strong> <?php echo $etapa_alimentacion; ?></span>
            </div>

            <div>
                <div class="d-flex gap-2">

                    <!-- Agregar -->
                    <button class="btn btn-success rounded-circle"
                        data-bs-toggle="tooltip" data-bs-title="Agregar Registro"
                        onclick="location.href='add_cerdos.php?caseta=<?php echo $i; ?>'">
                        <i class="fas fa-plus"></i>
                    </button>

                    <!-- Venta -->
                    <button class="btn btn-warning rounded-circle"
                        data-bs-toggle="tooltip" data-bs-title="Venta de Cerdos"
                        onclick="location.href='elim_cerdosVenta.php?caseta=<?php echo $i; ?>'">
                        <i class="fa-solid fa-dollar-sign"></i>
                    </button>

                    <!-- Muerte -->
                    <button class="btn btn-dark rounded-circle"
                        data-bs-toggle="tooltip" data-bs-title="Muerte de Cerdos"
                        onclick="location.href='elim_cerdosMuerte.php?caseta=<?php echo $i; ?>'">
                        <i class="fa-solid fa-skull"></i>
                    </button>

                    <!-- Vaciar -->
                    <button class="btn btn-danger rounded-circle"
                        data-bs-toggle="tooltip" data-bs-title="Vaciar Caseta"
                        onclick="abrirModalVaciar(<?php echo $i; ?>)">
                        <i class="fas fa-trash"></i>
                    </button>

                </div>
            </div>

            <div id="corrales-<?php echo $i; ?>" class="corrales" style="display: none;">
                <?php
                $query_total = "SELECT SUM(num_cerdos) AS total_cerdos FROM corrales WHERE caseta_id = $i";
                $resultado_total = $conexion->query($query_total);
                $fila_total = $resultado_total->fetch_assoc();
                $total_cerdos = $fila_total['total_cerdos'] ?? 0;
                ?>

                <div style="margin-bottom: 10px; font-size: 18px; font-weight: bold;">
                    Total de Cerdos en la Caseta: 
                    <span style="color: #28a745;"><?php echo $total_cerdos; ?></span>
                </div>

                <table>
                    <tr>
                        <th>Corral</th>
                        <th>Número de Cerdos</th>
                    </tr>

                    <?php
                    $query_corrales = "SELECT numero_corral, num_cerdos 
                                       FROM corrales 
                                       WHERE caseta_id = $i ORDER BY numero_corral ASC";

                    $resultado_corrales = $conexion->query($query_corrales);

                    while ($corral = $resultado_corrales->fetch_assoc()) {
                        echo "<tr><td>Corral " . $corral['numero_corral'] . "</td><td>" . $corral['num_cerdos'] . "</td></tr>";
                    }
                    ?>
                </table>
            </div>

        </div>

        <?php } ?>
    </div>
</div>


<div class="modal fade" id="modalVaciarCaseta" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
          <i class="fas fa-exclamation-triangle"></i> Confirmar acción
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        ¿Seguro que deseas vaciar esta caseta? 
        Esta acción vaciara todos los registros de cerdos en los corrales dentro de esta caseta.
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button id="btnConfirmVaciar" class="btn btn-danger">Sí, vaciar</button>
      </div>

    </div>
  </div>
</div>

<script>
let casetaSeleccionada = null;

// Abrir modal
function abrirModalVaciar(id) {
    casetaSeleccionada = id;
    let modal = new bootstrap.Modal(document.getElementById('modalVaciarCaseta'));
    modal.show();
}

// Confirmar vaciado
document.getElementById("btnConfirmVaciar").addEventListener("click", function () {

    fetch("vaciar_caseta.php?caseta=" + casetaSeleccionada)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Caseta vaciada",
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.message
                });
            }
        });

    let modal = bootstrap.Modal.getInstance(document.getElementById("modalVaciarCaseta"));
    modal.hide();
});

// Toggle corrales
function toggleCorrales(id) {
    const corrales = document.getElementById(`corrales-${id}`);
    const flecha = document.getElementById(`flecha-${id}`);

    if (corrales.style.display === 'none') {
        corrales.style.display = 'block';
        flecha.textContent = '▲';
    } else {
        corrales.style.display = 'none';
        flecha.textContent = '▼';
    }
}

// Activar tooltips
document.addEventListener("DOMContentLoaded", function () {
    [...document.querySelectorAll('[data-bs-toggle="tooltip"]')].map(el => new bootstrap.Tooltip(el));
});
</script>

</body>
</html>