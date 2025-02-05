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
    <title>Perfil de Usuario</title>
</head>
<body>

<style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: #f0f0f0;
            color: black;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000; /* Asegura que el navbar esté encima del sidebar */
        }

        .navbar h1 {
            margin: 0;
            font-size: 20px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px; /* Ajusta para que no choque con el navbar */
            left: 0;
            width: 250px;
            height: calc(100vh - 60px); /* Ajusta altura considerando el navbar */
            background-color: #f0f0f0;
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
            background-color: #575757;
        }

        /* Content */
        .content {
            margin-left: 250px; /* Mismo ancho del sidebar */
            margin-top: 60px; /* Altura del navbar */
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1>GestAP</h1>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Inicio</h2>
        <a href="#principal.php">Pagina Principal</a>
        <a href="#cerdos.php">Cerdos</a>
        <a href="#alimentos.php">Alimentos</a>
        <a href="#reportes.php">Reportes</a>
        <a href="#index.php">Cerrar Sesion</a>
    </div>          
     
    
   

<div>
  ITZEL CAM. I. 

</div>








    
    
</body>
</html>