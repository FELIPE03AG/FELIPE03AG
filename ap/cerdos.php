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


<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");

// Definir la cantidad de registros a mostrar por página
$registros_por_pagina = 10;

// Obtener el número de página actual
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $pagina = $_GET['page'];
} else {
    $pagina = 1;
}

// Calcular el punto de inicio para la consulta
$inicio = ($pagina - 1) * $registros_por_pagina;

// Realizar la consulta para obtener los registros de la página actual
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
  $buscar = $_GET['buscar'];
  $query = "SELECT * FROM cerdos WHERE num_caseta LIKE '%$buscar%' OR num_cerdos LIKE '%$buscar%' OR fecha_llegada_cerdos LIKE '%$buscar%' OR peso_prom LIKE '%$buscar%' OR edad_prom LIKE '%$buscar%' OR etapa_inicial LIKE '%$buscar%' ORDER BY id_registro DESC LIMIT $inicio, $registros_por_pagina";
} else {
  $query = "SELECT * FROM cerdos ORDER BY id_registro DESC LIMIT $inicio, $registros_por_pagina";
}

$resultado = mysqli_query($conexion, $query);

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
                "cerdos.php": ["cerdos.php", "elim_cerdos.php"] // Páginas relacionadas con "cerdos"
                
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
    </script><!-- tab bar-->
<div class="navbar">
        <h1>GestAP</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarCerdosModal">
          Agregar Banda
        </button>

       
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="elim_cerdos.php">Eliminar Cerdos</a>
        </li>
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



<!-- Modal de Edición -->
<div class="modal fade" id="editarCerdosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Cerdos</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulario de Edición -->
        <form id="formEditarCerdos" method="post" action="editar_cerdos.php">
          <div class="form-group">
            <label for="casetaDestinada">Caseta destinada:</label>
            <input type="text" class="form-control" id="edit_num_caseta" name="num_caseta" required>
            <!-- Mensaje de error -->
            <div id="edit_caseta-error" class="text-danger" style="display:none;">El número de caseta ya está ocupado.</div>
          </div>
          <div class="form-group">
            <label for="cantidadCerdos">Cantidad de cerdos:</label>
            <input type="number" class="form-control" id="edit_num_cerdos" name="num_cerdos" required>
          </div>
          <div class="form-group">
            <label for="fechaLlegada">Fecha de llegada:</label>
            <input type="date" class="form-control" id="edit_fecha_llegada_cerdos" name="fecha_llegada_cerdos" required>
          </div>
          <div class="form-group">
            <label for="pesoPromedio">Peso Promedio:</label>
            <input type="number" class="form-control" id="edit_peso_prom" name="peso_prom" required>
          </div>
          <div class="form-group">
            <label for="edadPromedio">Edad Promedio:</label>
            <input type="number" class="form-control" id="edit_edad_prom" name="edad_prom" required>
          </div>
          <div class="form-group">
            <label for="etapaAlimentacion">Etapa de Alimentación:</label>
            <select class="form-control" id="edit_etapa_inicial" name="etapa_inicial" required>
              <option value="Iniciador">Iniciador</option>
              <option value="Crecimiento">Crecimiento</option>
              <option value="Desarrollo">Desarrollo</option>
              <option value="Finalizador">Finalizador</option>
            </select>
          </div>
          <!-- Botón de enviar formulario -->
          <button type="button" class="btn btn-primary" onclick="editarRegistro()">Guardar Cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal para confirmar edición -->
<div class="modal fade" id="confirmEditModal" tabindex="-1" role="dialog" aria-labelledby="confirmEditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmEditModalLabel">Confirmar Edición</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas editar este registro?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="editarRegistro()">Editar</button>
      </div>
    </div>
  </div>
</div>

<!-- Script de Edición -->
<script>
function cargarDatosEdicion(id, caseta, cantidad, fecha, peso, edad, etapa) {
    // Rellenar los campos del formulario con los datos del registro
    $("#edit_num_cerdos").val(cantidad);
    $("#edit_num_caseta").val(caseta);
    $("#edit_fecha_llegada_cerdos").val(fecha);
    $("#edit_peso_prom").val(peso);
    $("#edit_edad_prom").val(edad);
    $("#edit_etapa_inicial").val(etapa);
    
    // Asignar el ID del registro al formulario de edición
    $("#formEditarCerdos").attr("data-id", id);

    // Deshabilitar el campo de número de caseta
    $("#edit_num_caseta").prop('readonly', true);
}

function editarRegistro() {
    // Obtener los datos del formulario de edición
    var id = $("#formEditarCerdos").attr("data-id");
    var cantidad = $("#edit_num_cerdos").val();
    var caseta = $("#edit_num_caseta").val();
    var fecha = $("#edit_fecha_llegada_cerdos").val();
    var peso = $("#edit_peso_prom").val();
    var edad = $("#edit_edad_prom").val();
    var etapa = $("#edit_etapa_inicial").val();

    // Mostrar el modal de confirmación para editar
    $('#confirmEditModal').modal('show');
    
    // Al hacer clic en el botón "Editar" dentro del modal de confirmación
    $('#confirmEditModal').on('click', '.btn-primary', function() {
        // Enviar una solicitud AJAX al servidor para editar el registro
        $.ajax({
            url: 'editar_cerdos.php',
            type: 'POST',
            data: {
                id_registro: id,
                num_cerdos: cantidad,
                num_caseta: caseta,
                fecha_llegada_cerdos: fecha,
                peso_prom: peso,
                edad_prom: edad,
                etapa_inicial: etapa
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
    });
}
</script>

<!-- Tabla de Registros -->
<?php
// Verificar si hay resultados
if(mysqli_num_rows($resultado) > 0) {
    // Imprimir la tabla de registros
    echo "<table border='1'>";
    echo "<tr><th>Número de Caseta </th><th>Cantidad de Cerdos</th><th>Fecha de llegada</th><th>Peso Promedio (kg)</th><th>Edad Promedio (Semanas)</th><th>Etapa de Alimentación</th><th>Acciones</th></tr>";
    while($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr>";
        echo "<td>".$fila['num_caseta']."</td>";
        echo "<td>".$fila['num_cerdos']."</td>";
        echo "<td>".$fila['fecha_llegada_cerdos']."</td>";
        echo "<td>".$fila['peso_prom']."</td>";
        echo "<td>".$fila['edad_prom']."</td>";
        echo "<td>".$fila['etapa_inicial']."</td>";
        echo "<td>
        
              <button onclick='cargarDatosEdicion(".$fila['id_registro'].", \"".$fila['num_caseta']."\", ".$fila['num_cerdos'].", \"".$fila['fecha_llegada_cerdos']."\", ".$fila['peso_prom'].", ".$fila['edad_prom'].", \"".$fila['etapa_inicial']."\")' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editarCerdosModal'>Editar</button>
              <button onclick='eliminarRegistro(".$fila['id_registro'].")' class='btn btn-danger'>Eliminar</button>
              <button onclick='mostrarDetalles(".$fila['id_registro'].")' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#detallesModal'>Detalles</button>
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

<!-- Modal para confirmar si desea eliminar -->
<div id="confirmModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este registro?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmDelete">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Funcion para eliminar registro-->
<script>
function eliminarRegistro(id) {
  $('#confirmModal').modal('show'); // Mostrar el modal de confirmación
  
  // Capturar el ID del registro al confirmar la eliminación
  $('#confirmDelete').click(function() {
    eliminarRegistroAjax(id);
    $('#confirmModal').modal('hide'); // Ocultar el modal después de confirmar la eliminación
  });
}

function eliminarRegistroAjax(id) {
  $.ajax({
    url: 'eliminar_cerdos.php',
    type: 'POST',
    data: { id_registro: id },
    
    success: function(response) {
      console.log(response);
      location.reload(); // Recargar la página después de eliminar el registro
    },
    error: function(xhr, status, error) {
      console.error(error);
    }
  });
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
            <label for="casetaDestinada">Caseta destinada:</label>
            <input type="text" class="form-control" id="num_caseta" name="num_caseta" required>
            <!-- Mensaje de error -->
            <div id="caseta-error" class="text-danger" style="display:none;">El número de caseta ya está ocupado.</div>
          </div>
          <div class="form-group">
            <label for="cantidadCerdos">Cantidad de cerdos:</label>
            <input type="number" class="form-control" id="num_cerdos" name="num_cerdos" required>
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

<!-- Modal de Error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ERROR</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        El número de caseta ya está ocupado. Por favor, elige otro número de caseta.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- borrar posiblemente -->

<!-- Modal eliminar por muerte -->
<div class="modal fade" id="elim_por_muerte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Cerdo Por Muerte</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<?php
// Verificar si se recibió el parámetro de error 
// echo <tr> <th>Numero de Corral</th><th>Numero de Cerdos</th> </tr>
if(isset($_GET['error']) && $_GET['error'] == 'caseta_existente') {
    echo "<script>$(document).ready(function(){ $('#errorModal').modal('show'); });</script>";
}
?>


<script>
async function mostrarDetalles(idRegistro) {
   const response = await fetch(`detalle-caseta.php?idRegistro=${idRegistro}`);
    const data = await response.json();
   
    console.log(data.length)

    $('#btnEliminarCerdo').off('click');

    if( data.length == 0){
      $('#detallesCorrales').html('No hay registros');
      return;
    }

    let plantilla = ``;

    plantilla = data.map(corral =>{
      return `<tr>
      
        <td>${corral.num_corral}</td>
        <td>${corral.num_cerdos}</td>
        
          
        
      </tr>`
    }).join('');


    $('#detallesCorrales').html(plantilla);

    const closeModal = ()=> {
      $('#detallesModal').modal('hide')
    }
    
    $('#btnEliminarCerdo').on('click', closeModal);

}

// Función para eliminar un cerdo de un corral específico y actualizar la base de datos
function eliminarCerdo(id_registro, numCaseta) {
    if (window.totalCerdos > 0) {
        window.totalCerdos--;

        // Actualizar el DOM
        document.getElementById('total-cerdos').innerText = window.totalCerdos;

        // Llamada AJAX para actualizar la base de datos
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminar_cerdo.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                } else {
                    alert("Error al comunicarse con el servidor.");
                }
            }
        };
        xhr.send("id_registro=" + id_registro + "&numCaseta=" + numCaseta);
    }
}

</script>


<!-- Dividir cerdos en corrales-->
<?php
if (isset($_POST['detalles'])) {
    $numCaseta = $_POST['num_caseta'];
    $numCerdos = $_POST['num_cerdos'];

    // Lógica para distribuir los cerdos entre los 30 corrales
    $corrales_a_usar = 28;  // Corrales del 2 al 29
    $cerdos_por_corral = floor($numCerdos / $corrales_a_usar);
    $sobrante = $numCerdos % $corrales_a_usar;

    // Crear un array de 30 corrales, con el primero y el último vacíos
    $corrales = array_fill(0, 30, 0);

    // Asignar cerdos a los corrales 2 a 29
    for ($i = 1; $i <= 28; $i++) {
        $corrales[$i + 1] = $cerdos_por_corral;
        if ($sobrante > 0) {
            $corrales[$i + 1]++;
            $sobrante--;
        }
    }

    // Guardar los resultados en una variable para mostrar en el modal
    $detalles_corrales = "<h5>Caseta $numCaseta - Distribución de Cerdos</h5>";
    $detalles_corrales .= "<table class='table table-bordered'>";
    $detalles_corrales .= "<tr><th>Corral</th><th>Número de Cerdos</th></tr>";
    for ($i = 0; $i < 30; $i++) {
        $detalles_corrales .= "<tr><td>Corral ".($i + 1)."</td><td>".$corrales[$i]."</td></tr>";
    }
    $detalles_corrales .= "</table>";

    // Almacenar los detalles en una sesión para usarlos en el modal
    $_SESSION['detalles_corrales'] = $detalles_corrales;
}
?>

<!-- Modal para Detalles de Corrales --><!-- Modal para Detalles de Corrales -->
 <!-- Modal para Detalles de Corrales -->
<div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detallesModalLabel">Detalles de los Corrales</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" >
        <table>
          <thead>
            <th>Numero de corral</th>
            <th>Numero de cerdos</th>
            
            

          </thead>
          <tbody id="detallesCorrales">

          </tbody>
        </table>
        <!-- Aquí se mostrará la lista de los corrales con los cerdos distribuidos -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>




<!-- PHP para cargar detalles del registro en el modal -->
<?php
echo "<script>
function detallesregistro(id) {
    $.ajax({
        url: 'detalles_cerdos.php',
        type: 'POST',
        data: { id_registro: id },
        success: function(response) {
            $('#detalles_registro').html(response);
            $('#detallesModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}
</script>";
?>






</div>



</body>
</html>