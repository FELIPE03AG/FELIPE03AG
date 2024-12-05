<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/snippets.js"></script>
    <script src="js/modals.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Script de Bootstrap JavaScript -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <title>Gestion de Cerdos</title>
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
<!-- tab bar-->

<!-- tab bar-->
<div class="navbar">
        <h1>GestAP</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarCerdosModal">
          Agregar Tolva
        </button>

       
        
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="principal.php">Regresar</a>
        </li>
        <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Buscar registros..." aria-label="Buscar" id="buscar">
      </form>
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

<!-- Script para búsqueda en vivo -->
<script>
$(document).ready(function(){
    $("#buscar").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

<div class= "content">


<p> Registros Realizados </p>
<div> </div>
<div> </div>

<style>
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


<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");

$resultado = mysqli_query($conexion, "SELECT * FROM alimentacion");

// Verificar si hay resultados
if(mysqli_num_rows($resultado) > 0) {
    // Iterar sobre cada fila de resultados
    while($fila = mysqli_fetch_assoc($resultado)) {
        // Imprimir cada registro en su propia tabla
        echo "<table border='1'>";
        echo "<tr><th>Numero de Tolva </th><th>Cantidad de Alimento<br>(Toneladas)</th><th>Fecha de llegada</th><th>Etapa de Alimento</th></tr>";
        echo "<tr>";
        echo "<td>".$fila['num_tolva']."</td>";
        echo "<td>".$fila['cantidad_alim']."</td>";
        echo "<td>".$fila['fecha_llegada_alim']."</td>";
        echo "<td>".$fila['etapa_alim']."</td>";
        echo "</tr>";
       
         // Agregar botones al final de cada fila
         echo "<td>
         <button onclick='editarRegistro(".$fila['id_registro_alim'].")'>Editar</button>
         <button onclick='eliminarRegistro(".$fila['id_registro_alim'].")'>Eliminar</button>
         <button onclick='detallesregistro(".$fila['id_registro_alim'].")'>Detalles</button></td>";
         echo "</tr>";
         echo "</table>";
    }
} else {
    // Mostrar un mensaje si no hay resultados
    echo "No se encontraron resultados.";
}



// Limpiar el buffer de salida, si es necesario
ob_end_flush();
?>

















<!-- Modal -->
<div class="modal fade" id="agregarTolva" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Tolva</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulario -->
        <form method="post" action="agregar_alimentos.php">
          <div class="form-group">
            <label for="cantidadCerdos">Tolva destinada:</label>
            <input type="number" class="form-control" id="num_tolva" name="num_tolva" required>
          </div>
          <div class="form-group">
            <label for="casetaDestinada">Cantidad de Alimento(Toneladas):</label>
            <input type="text" class="form-control" id="cantidad_alim" name="cantidad_alim" required>
          </div>
          <div class="form-group">
            <label for="fechaLlegada">Fecha de llegada:</label>
            <input type="date" class="form-control" id="fecha_llegada_alim" name="fecha_llegada_alim" required>
          </div>
          
          <div class="form-group">
            <label for="etapaAlimentacion">Etapa de Alimentación:</label>
            <select class="form-control" id="etapa_alim" name="etapa_alim" required>
              <option value="Iniciador">Iniciador</option>
              <option value="Crecimiento">Crecimiento</option>
              <option value="Desarrollo">Desarrollo</option>
              <option value="Finalizador">Finalizador</option>
            </select>
          </div>
          <!-- Botón de enviar formulario -->
          <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>





</div>



    
    
</body>
</html>