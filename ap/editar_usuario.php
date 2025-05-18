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
    <link rel="stylesheet" href="styles/style_principal.css">
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">

<script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "administrar_usuarios.php": ["administrar_usuarios.php", "editar_usuario.php"] // Páginas relacionadas con "cerdos"
                
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
        <div class="user-name">
            <?= htmlspecialchars($nombre) ?>
        </div>
    </div>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>


    <div class="content">

            <div class="container mt-4">
                <h2>Editar Usuario</h2>
                <form action="procesar_edicion.php" method="POST">
                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" value="<?= $usuario['u'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario['nombre'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="<?= $usuario['co'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol:</label>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="admin" <?= ($usuario['rol'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="user" <?= ($usuario['rol'] == 'user') ? 'selected' : '' ?>>User</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña (Dejar vacío si no se quiere cambiar):</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a href="administrar_usuarios.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        
    </div>

    
</body>
</html>