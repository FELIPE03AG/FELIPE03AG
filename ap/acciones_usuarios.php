<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

// Incluir configuración para la conexión a la base de datos
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>Acciones de Usuario</title>
    <style>
        body {
            background-image: url('img/f.jpeg');
            background-size: cover;
            background-position: center;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: #f0f0f0;
            color: black;
            display: flex;
            justify-content: 'between';
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .navbar h1 {
            margin: 0;
            font-size: 20px;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #f0f0f0;
            color: black;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
        }

        .sidebar a {
            color: black;
            padding: 15px 20px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #6e6e6e;
        }

        .sidebar a.active {
            background-color: #4caf50;
            color: black;
            font-weight: bold;
        }

        .content {
            margin-top: 60px;
            margin-left: 250px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            color: #333;
            min-height: calc(100vh - 60px);
        }

        .content h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            min-width: 100px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Paginación */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #4caf50;
            color: white;
            border-color: #4caf50;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1>GestAP</h1>
        <div class="user-name">
            <?= htmlspecialchars($nombre) ?>
        </div>
    </div>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2>Historial de Acciones</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Acción</th>
                <th>Fecha y Hora</th>
                <th>Usuario</th>
            </tr>
            <?php
            if ($result_historial->num_rows > 0) {
                while ($row = $result_historial->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id_historial']}</td>
                            <td>{$row['accion']}</td>
                            <td>{$row['fecha_hora']}</td>
                            <td>{$row['usuario']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay acciones registradas</td></tr>";
            }
            $conexion->close();
            ?>
        </table>

        <!-- Controles de paginación -->
        <div class="pagination">
            <?php if ($pagina_actual > 1): ?>
                <a href="?pagina=<?= $pagina_actual - 1 ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" <?= ($i == $pagina_actual) ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?pagina=<?= $pagina_actual + 1 ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual (home.php, services.php, etc.)

            sidebarLinks.forEach(link => {
                // Elimina la clase activa de todos los enlaces
                link.classList.remove("active");

                // Agrega la clase activa al enlace correspondiente
                if (link.getAttribute("href") === currentPath) {
                    link.classList.add("active");
                }
            });
        });
    </script>

</body>
</html>