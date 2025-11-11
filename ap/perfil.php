<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");

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
    <title>Historial de Acciones</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_cerdos.css">
</head>

<body>
 <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "administrar_usuarios.php": ["perfil.php", "perfil1.php"] // Páginas relacionadas con "cerdos"
                
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

 <!-- Nav bar -->
    <?php include 'navbar.php'; ?>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>



    <!-- Contenido principal -->
    <div class="content">
perfil
<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="text-center mb-4">Perfil del Usuario</h3>

    <!-- Nombre completo -->
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
      <div>
        <strong>Nombre completo:</strong> 
        <span id="nombreTexto"><?= htmlspecialchars($nombre) ?></span>
      </div>
      <button class="btn btn-outline-primary btn-sm rounded-circle"
              data-bs-toggle="modal"
              data-bs-target="#editarNombreModal">
        <i class="fas fa-pen"></i>
      </button>
    </div>

    <!-- Usuario -->
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
      <div>
        <strong>Usuario:</strong> 
        <span id="usuarioTexto"><?= htmlspecialchars($usuario) ?></span>
      </div>
      <button class="btn btn-outline-primary btn-sm rounded-circle"
              data-bs-toggle="modal"
              data-bs-target="#editarUsuarioModal">
        <i class="fas fa-pen"></i>
      </button>
    </div>

    <!-- Correo -->
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
      <div>
        <strong>Correo:</strong> 
        <span id="correoTexto"><?= htmlspecialchars($correo) ?></span>
      </div>
      <button class="btn btn-outline-primary btn-sm rounded-circle"
              data-bs-toggle="modal"
              data-bs-target="#editarCorreoModal">
        <i class="fas fa-pen"></i>
      </button>
    </div>

    <!-- Rol -->
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <strong>Rol de usuario:</strong> 
        <span><?= htmlspecialchars($rol) ?></span>
      </div>
      <button class="btn btn-outline-secondary btn-sm rounded-circle" disabled title="No editable">
        <i class="fas fa-lock"></i>
      </button>
    </div>
  </div>
</div>

    </div>
    
</body>
</html>