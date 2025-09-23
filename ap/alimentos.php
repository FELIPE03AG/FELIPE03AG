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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestion de Alimentos</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_alimentos.css">
</head>

<body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "alimentos.php": ["alimentos.php", "alim.php"] // Páginas relacionadas con "cerdos"
                
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
    <h1>Gestión de Tolvas de Alimento</h1>
   <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alimento</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <!-- Botones -->
    <div class="d-flex justify-content-between mb-3">
        <h2>Gestión de Alimento</h2>
    </div>
        <div>
            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                ➕ Agregar Registro
            </button>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                ❌ Eliminar Registro
            </button>
        </div>
    </div>

    <!-- Tabla de registros -->
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Número de Caseta</th>
                        <th>Cantidad de Alimento (kg)</th>
                        <th>Etapa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, fecha_llegada_alim, TIME(fecha_llegada_alim) as hora, num_tolva as caseta, cantidad_alim, etapa_alim 
                            FROM tolvas ORDER BY fecha_llegada_alim DESC";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>".$row['id']."</td>
                                    <td>".$row['fecha_llegada_alim']."</td>
                                    <td>".$row['hora']."</td>
                                    <td>".$row['caseta']."</td>
                                    <td>".$row['cantidad_alim']."</td>
                                    <td>".$row['etapa_alim']."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No hay registros</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
          <label class="form-label">Número de Caseta:</label>
          <input type="number" name="num_caseta" class="form-control" required>
          
          <label class="form-label mt-2">Cantidad de Alimento (kg):</label>
          <input type="number" name="cantidad" class="form-control" required>
          
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
          <label class="form-label">ID del registro a eliminar:</label>
          <input type="number" name="id" class="form-control" required>
          <small class="text-muted">Consulta la tabla para ver el ID correspondiente.</small>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Eliminar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

</body>
</html>