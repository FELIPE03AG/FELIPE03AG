<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

echo $rol;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>Eliminacion de Cerdos</title>
</head>
<body>

<style>
   body {
            background-image: url('img/f.jpeg');
            background-size: cover; /* para cubrir todo el fondo */
            background-position: center; /* para centrar la imagen */
            /* Añade más estilos si es necesario */
        }
        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: #f0f0f0; /* Gris oscuro */
            color: black;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .navbar h1 {
            margin: 0;
            font-size: 20px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px; /* Debajo del navbar */
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #f0f0f0; /* Gris medio */
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
            background-color: #6e6e6e; /* Gris oscuro para el hover */
        }
         /* Resaltar el apartado activo */
        .sidebar a.active {
            background-color: #4caf50; /* Verde resalte */
            color: black;
            font-weight: bold;
        }


        /* Content */
        .content {
            margin-top: 60px; /* Espacio debajo del navbar */
            margin-left: 250px; /* Espacio para el sidebar */
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            color: #333;
            min-height: calc(100vh - 60px); /* Asegura que el contenido llene el espacio */
        }

        /* Estilo de texto */
        .content h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        /* Estilos para la tabla */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px; /* Espacio entre tablas */
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        min-width: 100px; /* Ancho mínimo para celdas */
    }
    th {
        background-color: #f2f2f2; /* Color de fondo para encabezados */
    }
    tr:nth-child(even) {
        background-color: #f2f2f2; /* Color de fondo para filas pares */
    }
  </style>
<script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "cerdos.php": ["cerdos.php", "elim_cerdos.php"] // Páginas relacionadas con "Cerdos"
                
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

</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1>GestAP</h1>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="cerdos.php">Regresar</a>
        </li>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Inicio</h2>
        <a href="principal.php">Pagina Principal</a>
        <a href="cerdos.php">Cerdos</a>
        <a href="alimentos.php">Alimentos</a>
        <a href="reportes_actividades.php">Reportes</a>
        <a href="index.php">Cerrar Sesion</a>
    </div>

    <!-- Content -->
    <div class="content">

    <p>
        Eliminacion de Cerdos
    </p>

    <div class="accordion" id="accordionExample">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Eliminar Por Muerte
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        
    <div class="form-container">
        <h2>Eliminar Cerdo</h2>
        <p>Solo puede eliminar 1 cerdo a la vez</p>
        <form action="/eliminar_cerdo" method="POST">
            <!-- Número de caseta -->
            <div class="form-group">
                <label for="num_caseta">Número de caseta</label>
                <input type="number" id="num_caseta" name="num_caseta" required min="1" placeholder="Ingrese el número de caseta">
            </div>

            <!-- Número de corral -->
            <div class="form-group">
                <label for="num_corral">Número de corral</label>
                <input type="number" id="num_corral" name="num_corral" required min="1" placeholder="Ingrese el número de corral">
            </div>

            <!-- Causa de muerte -->
            <div class="form-group">
                <label for="causa_muerte">Causa de muerte</label>
                <select id="causa_muerte" name="causa_muerte" required>
                    <option value="">Seleccione una causa</option>
                    <option value="Pulmonar">Problemas Pulmonares</option>
                    <option value="Troja">Tripa Roja</option>
                    <option value="Prolapso">Prolapso</option>
                    <option value="Agresion">Agresion de otro cerdo</option>
                    <option value="Otra">Otra</option>
                </select>
            </div>

            <!-- Botón de envío -->
            <button type="submit">Eliminar Cerdos</button>
        </form>
    </div>
    
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Eliminar Por Venta
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
      <div class="accordion-body">
      <div class="form-container">
        <h2>Eliminar Cerdos</h2>
        <p>Solo elimina cerdos de un solo Corral</p>
        <form action="/eliminar_cerdo" method="POST">
            <!-- Número de caseta -->
            <div class="form-group">
                <label for="num_caseta">Número de caseta</label>
                <input type="number" id="num_caseta" name="num_caseta" required min="1" placeholder="Ingrese el número de caseta">
            </div>

            <!-- Número de corral -->
            <div class="form-group">
                <label for="num_corral">Número de corral</label>
                <input type="number" id="num_corral" name="num_corral" required min="1" placeholder="Ingrese el número de corral">
            </div>
            <!-- Número de corral -->
            <div class="form-group">
                <label for="num_corral">Número de Cerdos</label>
                <input type="number" id="num_corral" name="num_corral" required min="1" placeholder="Ingrese el número de corral">
            </div>

           

            <!-- Botón de envío -->
            <button type="submit">Eliminar Cerdo</button>
        </form>
    </div>
        
      </div>
    </div>
  </div>
</div>
        








    
    </div>
    





    
    
</body>
</html>