<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <a class="navbar-brand" href="#">Gestion Porcina AP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="cerdos.php">Nuevo Registro</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="alimentos.php">Eliminar Registro</a>
        </li>
      
        
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="principal.php">Salir al Menu</a>
        </li>
      </ul>
     
      
    </div>
  </div>
</nav>








<div class="container text-center">
  <div class="row align-items-start">
    <div class="col">
      One of three columns
    </div>
    
    <div class="col">
      <!-- Botón para abrir el modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agregar_cerdos">
    Agregar Cerdos
</button>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="agregar_cerdos" tabindex="-1" role="dialog" aria-labelledby="agregar_cerdos_label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregar_cerdos_label">Agregar Cerdos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulario dentro del modal -->
        <form id="form_agregar_cerdos">
          <div class="form-group">
            <label for="nombre">Numero de Entrada:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="form-group">
            <label for="edad">Caseta destinada:</label>
            <input type="number" class="form-control" id="edad" name="edad" required>
          </div>
          <div class="form-group">
  <label for="fecha_nacimiento">Fecha de llegada:</label>
  <input type="date" class="form-control" id="fecha_llegada" name="fecha_nacimiento" required>
</div>
          <div class="form-group">
            <label for="raza">Cantidad de cerdos:</label>
            <input type="text" class="form-control" id="raza" name="raza" required>
          </div>
          <div class="form-group">
            <label for="color">Peso Promedio:</label>
            <input type="text" class="form-control" id="color" name="color" required>
          </div>
          <div class="form-group">
            <label for="color">Edad Promedio:</label>
            <input type="text" class="form-control" id="color" name="color" required>
          </div>
          <div class="form-group">
  <label for="opciones">Etapa de Alimentacion:</label>
  <select class="form-control" id="opciones" name="opciones">
    <option value="opcion1">Iniciador</option>
    <option value="opcion2">Crecimiento</option>
    <option value="opcion3">Desarrollo</option>
    <option value="opcion4">Finalizador</option>
  </select>
</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="submit_cerdos">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Enlace a jQuery, Bootstrap JS, y Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
// Script para manejar el formulario
$('#submit_cerdos').click(function() {
    var formData = $('#form_agregar_cerdos').serializeArray();
    console.log(formData); // Aquí puedes hacer algo con los datos, como enviarlos a un servidor
    $('#agregar_cerdos').modal('hide'); // Cerrar el modal después de guardar
});
</script>







    
    
</body>
</html>