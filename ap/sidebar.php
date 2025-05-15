 <!-- Sidebar -->
 <div class="sidebar">
     <h2>Inicio</h2>
     <a href="principal.php">Pagina Principal</a>
     <a href="cerdos.php">Cerdos</a>
     <a href="alimentos.php">Alimentos</a>

     <?php
        if ($rol == 'admin') {
        ?>
         <a href="reportes_actividades.php">Reportes</a>
         <a href="administrar_usuarios.php">Administrar Usuarios</a>
         <a href="acciones_usuarios.php">Acciones de Usuarios</a>
     <?php
        }
        ?>



   
     <a href="logout.php">Cerrar Sesion</a>
 </div>

 <style>
 /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px;
            /* Debajo del navbar */
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #f0f0f0;
            /* Gris medio */
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
            background-color: #6e6e6e;
            /* Gris oscuro para el hover */
        }

        /* Resaltar el apartado activo */
        .sidebar a.active {
            background-color: #4caf50;
            /* Verde resalte */
            color: black;
            font-weight: bold;
        }
        </style>

 