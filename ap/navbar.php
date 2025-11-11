<!-- Navbar -->
<div class="navbar d-flex justify-content-between align-items-center px-4 py-2 bg-light shadow">
    <!-- Solo logo -->
    <div class="d-flex align-items-center">
        <img src="img/Logo1.png" alt="Logo GestAP" style="height: 45px; width: auto;">
    </div>

    <!-- Usuario con dropdown -->
    <div class="dropdown">
        <button class="btn btn-light d-flex align-items-center dropdown-toggle" 
                type="button" 
                id="userDropdown" 
                data-bs-toggle="dropdown" 
                aria-expanded="false" 
                style="border: none; box-shadow: none;">
            <i class="fas fa-user-circle me-2"></i>
            <span><?= htmlspecialchars($nombre) ?></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="administrar_usuarios.php">Administrar Usuarios</a></li>
            <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>
</div>