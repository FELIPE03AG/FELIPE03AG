<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-2" style="height: 65px;">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="img/Logo1.png" alt="Logo GestAP" style="height: 48px; width: auto;">
        </a>

        <!-- Usuario -->
        <div class="dropdown">
            <button 
                class="btn d-flex align-items-center px-3 py-2 shadow-sm" 
                type="button" 
                id="userDropdown" 
                data-bs-toggle="dropdown" 
                aria-expanded="false"
                style="background: #f8f9fa; border-radius: 12px; border: 1px solid #ddd;">
                
                <i class="fas fa-user-circle me-2" style="font-size: 20px; color: #6c757d;"></i>
                <span style="font-weight: 500;"><?= htmlspecialchars($nombre) ?></span>

                <!-- Flecha hacia abajo -->
                <i class="fas fa-chevron-down ms-2" style="font-size: 14px; color: #6c757d;"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="administrar_usuarios.php">
                        <i class="fas fa-users-cog me-2 text-secundary"></i> Administrar usuarios
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center" 
                        href="editar_usuario.php?id=<?= $_SESSION['id'] ?>">
                        <i class="fas fa-user me-2 text-secondary"></i> Editar perfil
                    </a>

                </li>

                <li><hr class="dropdown-divider"></li>

                <li>
                    <a class="dropdown-item text-danger d-flex align-items-center" href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>