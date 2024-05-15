<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");

// Definir la cantidad de registros a mostrar por página
$registros_por_pagina = 5;

// Obtener el número de página actual
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $pagina = $_GET['page'];
} else {
    $pagina = 1;
}

// Calcular el punto de inicio para la consulta
$inicio = ($pagina - 1) * $registros_por_pagina;

// Realizar la consulta para obtener los registros de la página actual
$resultado = mysqli_query($conexion, "SELECT * FROM cerdos LIMIT $inicio, $registros_por_pagina");

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
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarCerdosModal">
      Agregar Banda
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
// Verificar si hay resultados
if(mysqli_num_rows($resultado) > 0) {
    // Imprimir la tabla de registros
    echo "<table border='1'>";
    echo "<tr><th>Numero de Caseta </th><th>Cantidad de Cerdos</th><th>Fecha de llegada</th><th>Peso Promedio</th><th>Edad Promedio</th><th>Etapa de Alimentación</th><th>Acciones</th></tr>";
    while($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr>";
        echo "<td>".$fila['num_caseta']."</td>";
        echo "<td>".$fila['num_cerdos']."</td>";
        echo "<td>".$fila['fecha_llegada_cerdos']."</td>";
        echo "<td>".$fila['peso_prom']."</td>";
        echo "<td>".$fila['edad_prom']."</td>";
        echo "<td>".$fila['etapa_inicial']."</td>";
        echo "<td>
              <button onclick='editarRegistro(".$fila['id_registro'].")'>Editar</button>
              <button onclick='eliminarRegistro(".$fila['id_registro'].")'>Eliminar</button>
              <button onclick='detallesregistro(".$fila['id_registro'].")'>Detalles</button>
              </td>";
        echo "</tr>";
    }
    echo "</table>";

    // Agregar controles de paginación
    $total_registros = mysqli_num_rows(mysqli_query($conexion, "SELECT * FROM cerdos"));
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    echo "<ul class='pagination justify-content-center'>";
    for ($i = 1; $i <= $total_paginas; $i++) {
        echo "<li class='page-item'><a class='page-link' href='?page=".$i."'>".$i."</a></li>";
    }
    echo "</ul>";
} else {
    // Mostrar un mensaje si no hay resultados
    echo "No se encontraron resultados.";
}

// Limpiar el buffer de salida, si es necesario
ob_end_flush();
?>

<!-- Funcion para eliminar registro-->
<script>
function eliminarRegistro(id) {

  $(function(){

    // Mostrar un mensaje de confirmación al usuario
    if(confirm('¿Estás seguro de que deseas eliminar este registro?')) {
      console.log('Hola',id)

        // Enviar una solicitud AJAX al servidor para eliminar el registro
        $.ajax({
            url: 'eliminar_cerdos.php',
            type: 'POST',
            data: { id_registro: id },
            
            success: function(response) {
                // Manejar la respuesta del servidor
                console.log(response);
                // Actualizar la página o realizar alguna acción adicional si es necesario
                location.reload(); // Recargar la página después de eliminar el registro
            },
            error: function(xhr, status, error) {
                // Manejar errores
                console.error(error);
            }
        });
    }
  })
}
</script>

<!-- Funcion para editar registro-->

<script>

function editarRegistro(id) {
    // Obtener los datos del registro que se desea editar (puedes hacer una solicitud AJAX para obtenerlos si es necesario)
    var cantidad = prompt("Editar cantidad de cerdos:", "");
    var caseta = prompt("Editar caseta destinada:", "");
    var fecha = prompt("Editar fecha de llegada:", "");
    var peso = prompt("Editar peso promedio:", "");
    var edad = prompt("Editar edad promedio:", "");
    var etapa = prompt("Editar etapa de alimentación:", "");

    // Mostrar un mensaje de confirmación al usuario antes de enviar la solicitud de edición
    if(confirm('¿Estás seguro de que deseas editar este registro?')) {
        // Enviar una solicitud AJAX al servidor para editar el registro
        $.ajax({
            url: 'editar_cerdos.php',
            type: 'POST',
            data: {
                id_registro: id,
                caseta: caseta,
                cantidad: cantidad,
                fecha: fecha,
                peso: peso,
                edad: edad,
                etapa: etapa
            },
            success: function(response) {
                // Manejar la respuesta del servidor
                console.log(response);
                // Actualizar la página o realizar alguna acción adicional si es necesario
                location.reload(); // Recargar la página después de editar el registro
            },
            error: function(xhr, status, error) {
                // Manejar errores
                console.error(error);
            }
        });
    }
}

</script>

<!-- Modal -->
<div class="modal fade" id="agregarCerdosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Cerdos</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulario -->
        <form method="post" action="agregar_cerdos.php">
          <div class="form-group">
            <label for="cantidadCerdos">Cantidad de cerdos:</label>
            <input type="number" class="form-control" id="num_cerdos" name="num_cerdos" required>
          </div>
          <div class="form-group">
            <label for="casetaDestinada">Caseta destinada:</label>
            <input type="text" class="form-control" id="num_caseta" name="num_caseta" required>
          </div>
          <div class="form-group">
            <label for="fechaLlegada">Fecha de llegada:</label>
            <input type="date" class="form-control" id="fecha_llegada_cerdos" name="fecha_llegada_cerdos" required>
          </div>
          <div class="form-group">
            <label for="pesoPromedio">Peso Promedio:</label>
            <input type="number" class="form-control" id="peso_prom" name="peso_prom" required>
          </div>
          <div class="form-group">
            <label for="edadPromedio">Edad Promedio:</label>
            <input type="number" class="form-control" id="edad_prom" name="edad_prom" required>
          </div>
          <div class="form-group">
            <label for="etapaAlimentacion">Etapa de Alimentación:</label>
            <select class="form-control" id="etapa_inicial" name="etapa_inicial" required>
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
