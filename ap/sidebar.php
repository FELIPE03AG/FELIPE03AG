 <!-- Sidebar -->
 <div class="sidebar">
    <h2 class="text-center">
        <i class="fas fa-home"></i> Inicio
    </h2>

    <a href="cerdos.php">
        <i class="fas fa-piggy-bank"></i> Cerdos
    </a>

    <a href="alimento.php">
        <i class="fas fa-wheat-awn"></i> Alimentos
    </a>

    <a href="vacunacion.php">
        <i class="fas fa-syringe"></i> Vacunación
    </a>

    <?php if ($rol == 'admin') { ?>
        <a href="reportes_actividades.php">
            <i class="fas fa-chart-line"></i> Reportes
        </a>

        <a href="administrar_usuarios.php">
            <i class="fas fa-users-cog"></i> Administrar Usuarios
        </a>

        <a href="acciones_usuarios.php">
            <i class="fas fa-user-check"></i> Acciones de Usuarios
        </a>
    <?php } ?>

    <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
    </a>
</div>
