<?php
include("config.php");
session_start();

if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}
$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

// Verificar si se ha recibido el ID del usuario
if (!isset($_GET['id'])) {
    die("Error: ID de usuario no especificado.");
}

$id = $_GET['id'];

// Consultar datos del usuario
$sql = "SELECT id, u, nombre, co, rol FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Usuario no encontrado.");
}

$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="font_awesome/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_principal.css">

<script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "administrar_usuarios.php": ["administrar_usuarios.php", "editar_usuario.php"] // Páginas relacionadas con "usuarios"
                
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
    <?php include 'navbar.php'; ?>

    <?php include 'sidebar.php'; ?>


    <div class="content">

        <div class="container mt-5">
            <div class="card shadow-lg p-4"> <h2 class="card-title mb-4"><i class="fas fa-user-edit me-2"></i> Editar perfil</h2>
                <form action="procesar_edicion.php" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="usuario" class="form-label">Usuario:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="usuario" name="usuario" value="<?= htmlspecialchars($usuario['u']) ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo Electrónico:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($usuario['co']) ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rol" class="form-label">Rol:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                <select class="form-control" id="rol" name="rol" required>
                                    <option value="admin" <?= ($usuario['rol'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                                    <option value="user" <?= ($usuario['rol'] == 'user') ? 'selected' : '' ?>>User</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Nueva Contraseña (Dejar vacío si no se quiere cambiar):</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <small class="form-text text-muted">La contraseña solo se actualizará si se introduce un valor aquí.</small>
                    </div>

                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary me-2"><i class="fas fa-save me-1"></i> Guardar Cambios</button>
                        <a href="administrar_usuarios.php" class="btn btn-secondary"><i class="fas fa-times-circle me-1"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        
    </div>

</body>
</html>