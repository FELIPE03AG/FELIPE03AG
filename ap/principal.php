<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>Pagina Principal</title>
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

<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark" style="background-color: #e3f2fd;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Gestion Porcina AP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="cerdos.php">Cerdos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="alimentos.php">Alimentos</a>
        </li>
        
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Insertar
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="cerdos.php">Entrada de Cerdos</a></li>
            <li><a class="dropdown-item" href="alimentos.php">Entrada de Alimentos</a></li>
           
            
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Eliminar Cerdos
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="cerdos.php">Eliminacion por Muerte</a></li>
            <li><a class="dropdown-item" href="alimentos.php">Eliminacion por Venta</a></li>
           
            
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Salir al Menu</a>
        </li>
      </ul>

      <div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    Perfil
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="#">Configuracion</a></li>
    <li><a class="dropdown-item" href="#">Cerrar Sesion</a></li>
   
  </ul>
</div>
          
     
    
   
</nav>

<div> 
  vale manguera
</div>






    
    
</body>
</html>