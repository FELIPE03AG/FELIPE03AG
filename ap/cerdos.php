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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agregarCerdosModal">
    Agregar Cerdos
</button>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="agregarCerdosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Cerdos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>






    
    
</body>
</html>