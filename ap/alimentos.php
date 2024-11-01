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
  </style>
<!-- tab bar-->

<!-- tab bar-->

<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark" style="background-color: #e3f2fd;">
  <div class="container-fluid">
    <a class="navbar-brand">Gestion Porcina AP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
      <li><span>   </span></li>
      <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarTolva">
      Agregar Tolva
    </button>
    <li><span>     </span></li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="principal.php">Salir al Menu</a>
        </li>
      </ul>
     
      
    </div>
  </div>
</nav>
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

<!-- Script de Bootstrap JavaScript -->






    
    
</body>
</html>