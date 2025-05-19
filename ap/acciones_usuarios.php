<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");

// Definir el número de registros por página
$registros_por_pagina = 10;

// Obtener el número total de registros
$sql_total = "SELECT COUNT(*) AS total FROM historial";
$result_total = $conexion->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_registros = $row_total['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener la página actual desde la solicitud AJAX
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) {
    $pagina_actual = 1;
} elseif ($pagina_actual > $total_paginas) {
    $pagina_actual = $total_paginas;
}

// Calcular el offset (desplazamiento) para la consulta SQL
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Consulta para obtener los registros de la página actual
$sql_historial = "SELECT id_historial, accion, fecha_hora, usuario 
                  FROM historial 
                  ORDER BY fecha_hora DESC 
                  LIMIT $registros_por_pagina OFFSET $offset";
$result_historial = $conexion->query($sql_historial);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Acciones de Usuario</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="font_awesome/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles/style_navbar.css" />
    <link rel="stylesheet" href="styles/style_sidebar.css" />
    <link rel="stylesheet" href="styles/style_acciones_usuario.css" />
    <script src="js/bootstrap.bundle.min.js"></script>
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

    <!-- Contenido principal -->
    <div class="content">
        <h2>Historial de Acciones</h2>

        <!-- Tabla de acciones -->
        <table>
            <thead>
                <tr>
                    <th>Acción</th>
                    <th>Fecha y Hora</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_historial->num_rows > 0) {
                    while ($row = $result_historial->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['accion']}</td>
                                <td>{$row['fecha_hora']}</td>
                                <td>{$row['usuario']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No hay acciones registradas</td></tr>";
                }
                $conexion->close();
                ?>
            </tbody>
        </table>

        <!-- Controles de paginación -->
        <div class="pagination">
            <?php if ($pagina_actual > 1): ?>
                <a href="?pagina=<?= $pagina_actual - 1 ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" <?= ($i == $pagina_actual) ? 'class="active"' : '' ?>>
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?pagina=<?= $pagina_actual + 1 ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Script para activar el link de la barra lateral -->
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
    </script>

</body>
</html>