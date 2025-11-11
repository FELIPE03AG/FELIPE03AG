<!-- Navbar -->
<div class="navbar d-flex justify-content-between align-items-center px-4 py-2 bg-light shadow">
    <!-- Solo logo -->
    <div class="d-flex align-items-center">
        <img src="img/Logo1.png" alt="Logo GestAP" 
             style="height: 45px; width: auto;">
    </div>

    <!-- Usuario sin dropdown -->
    <div class="d-flex align-items-center">
        <i class="fas fa-user-circle me-2"></i>
        <span><?= htmlspecialchars($nombre) ?></span>
    </div>
</div>