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
     <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <?php
session_start();
include("config.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Cerdos</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #d9534f;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #c9302c;
        }
    </style>

</head>
<body>


<div class="form-container">
    <h2>Eliminar Cerdos</h2>
    <form action="eliminar_cerdos.php" method="POST">
        <div class="form-group">
            <label for="tipo_eliminacion">Tipo de Eliminación:</label>
            <select name="tipo_eliminacion" id="tipo_eliminacion" onchange="toggleForm()" required>
                <option value="" disabled selected>Seleccione una opción</option>
                <option value="venta">Venta</option>
                <option value="muerte">Muerte</option>
            </select>
        </div>

        <!-- Opciones para Muerte -->
        <div id="form_muerte" style="display: none;">
            <div class="form-group">
                <label for="fecha_muerte">Fecha de Muerte:</label>
                <input type="datetime-local" name="fecha_muerte" id="fecha_muerte">
            </div>
            <div class="form-group">
                <label for="num_caseta_muerte">Número de Caseta:</label>
                <input type="number" name="num_caseta_muerte" id="num_caseta_muerte">
            </div>
            <div class="form-group">
                <label for="num_corral_muerte">Número de Corral:</label>
                <input type="number" name="num_corral_muerte" id="num_corral_muerte">
            </div>
            <div class="form-group">
                <label for="causa_muerte">Causa de Muerte:</label>
                <select name="causa_muerte" id="causa_muerte">
                    <option value="Tripa Roja">Tripa Roja</option>
                    <option value="Problemas Pulmonares">Problemas Pulmonares</option>
                    <option value="Agresion">Agresion</option>
                    <option value="Prolapso">Prolapso</option>
                    <option value="Desnutrición">Desnutrición</option>
                    <option value="Otra">Otra</option>
                </select>
            </div>
        </div>

        <!-- Opciones para Venta -->
        <div id="form_venta" style="display: none;">
            <div class="form-group">
                <label for="fecha_venta">Fecha de Venta:</label>
                <input type="datetime-local" name="fecha_venta" id="fecha_venta">
            </div>
            <div class="form-group">
                <label for="num_caseta_venta">Número de Caseta:</label>
                <input type="number" name="num_caseta_venta" id="num_caseta_venta">
            </div>
            <div class="form-group">
                <label for="num_corral_venta">Número de Corral:</label>
                <input type="number" name="num_corral_venta" id="num_corral_venta">
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad de Cerdos a Eliminar:</label>
                <input type="number" name="cantidad" id="cantidad">
            </div>
        </div>

        <button type="submit">Eliminar</button>
    </form>
</div>

<script>
    function toggleForm() {
        const tipo = document.getElementById('tipo_eliminacion').value;
        document.getElementById('form_muerte').style.display = tipo === 'muerte' ? 'block' : 'none';
        document.getElementById('form_venta').style.display = tipo === 'venta' ? 'block' : 'none';
    }
</script>
</body>
</html>








    
    </div>
    





    
    
</body>
</html>