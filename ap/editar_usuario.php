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
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('img/f.jpeg');
            background-size: cover;
            background-position: center;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: #f0f0f0;
            color: black;
            display: flex;
            justify-content: 'between';
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .navbar h1 {
            margin: 0;
            font-size: 20px;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #f0f0f0;
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
        }

        .sidebar a.active {
            background-color: #4caf50;
            color: black;
            font-weight: bold;
        }

        .content {
            margin-top: 60px;
            margin-left: 250px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            color: #333;
            min-height: calc(100vh - 60px);
        }

        .content h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            min-width: 100px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Paginación */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #4caf50;
            color: white;
            border-color: #4caf50;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>


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
