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



     <a href="notificaciones.php">Notificaciones</a>
     <a href="logout.php">Cerrar Sesion</a>
 </div>