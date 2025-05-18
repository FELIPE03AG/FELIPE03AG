   <!-- Navbar -->
<div class="navbar d-flex justify-content-between align-items-center px-4 py-2 bg-light shadow">
    <h1 class="mb-0">GestAP</h1>

    <!-- Usuario con dropdown -->
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle me-2"></i>
            <?= htmlspecialchars($nombre) ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
        </ul>
    </div>
</div>