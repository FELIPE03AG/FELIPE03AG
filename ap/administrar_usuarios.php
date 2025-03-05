<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

echo $rol;

// Incluir configuración para la conexión a la base de datos
include("config.php");



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS (requiere Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <title>Perfil de Usuarios</title>
</head>

<body>

    <style>
        body {
            background-image: url('img/f.jpeg');
            background-size: cover;
            /* para cubrir todo el fondo */
            background-position: center;
            /* para centrar la imagen */
            /* Añade más estilos si es necesario */
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: #f0f0f0;
            /* Gris oscuro */
            color: black;
            display: flex;
            justify-content: 'between';
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .navbar h1 {
            margin: 0;
            font-size: 20px;
        }

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


        /* Content */
        .content {
            margin-top: 60px;
            /* Espacio debajo del navbar */
            margin-left: 250px;
            /* Espacio para el sidebar */
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            color: #333;
            min-height: calc(100vh - 60px);
            /* Asegura que el contenido llene el espacio */
        }

        /* Estilo de texto */
        .content h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            /* Espacio entre tablas */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            min-width: 100px;
            /* Ancho mínimo para celdas */
        }

        th {
            background-color: #f2f2f2;
            /* Color de fondo para encabezados */
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
            /* Color de fondo para filas pares */
        }
    </style>
    </head>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual (home.php, services.php, etc.)

            sidebarLinks.forEach(link => {
                // Elimina la clase activa de todos los enlaces
                link.classList.remove("active");

                // Agrega la clase activa al enlace correspondiente
                if (link.getAttribute("href") === currentPath) {
                    link.classList.add("active");
                }
            });
        });
    </script>

    <body>

        <!-- Navbar -->
        <div class="navbar">
            <h1>GestAP</h1>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Agregar Usuario
            </button>

    
            <button type="button" class="btn btn-danger btn-delete" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
            Eliminar Usuario
            </button>




            <div>
                <div class="user-name">
                    <?= htmlspecialchars($nombre) ?>
                </div>
            </div>
        </div>

        <!-- Modal para agregar usuario -->
<div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="crear_usuarios.php" method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol:</label>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="admin">admin</option>
                            <option value="user">user</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para cargar datos del usuario -->
<script>
function cargarUsuario(id) {
    // Hacer una solicitud AJAX para obtener los datos del usuario
    fetch(`obtener_usuario.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('id_usuario').value = data.id;
            document.getElementById('usuario').value = data.u;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('correo').value = data.co;
            document.getElementById('rol').value = data.rol;
        })
        .catch(error => console.error('Error al cargar usuario:', error));
}

// Manejar el envío del formulario
document.getElementById('formEditarUsuario').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('editar_usuario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        alert(result);
        location.reload(); // Recargar la página tras la edición
    })
    .catch(error => console.error('Error en la actualización:', error));
});
</script>


<!-- Modal de Eliminación -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Eliminar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Seleccione el <strong>nombre de usuario</strong> que desea eliminar:</p>
                <div class="mb-3">
                    <label for="deleteUserName" class="form-label">Usuario:</label>
                    <select class="form-control" id="deleteUserName" required>
                        <option value="" disabled selected>Seleccione un usuario</option>
                        <?php
                        $sql = "SELECT u FROM usuarios";
                        $result = $conexion->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['u']}'>{$row['u']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="abrirConfirmacion()">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar al usuario <strong id="usuarioAEliminar"></strong>?</p>
                <form action="eliminar_usuario.php" method="POST">
                    <input type="hidden" id="usuarioInput" name="u">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Content -->



        <div class="content">

            <h2>Usuarios Registrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Modificar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, u, nombre, co, rol FROM usuarios";
                    $result = $conexion->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['u']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['co']}</td>
                            <td>{$row['rol']}</td>
                            <td>
                                <a href='editar_usuario.php?id={$row['id']}' class='btn btn-primary'>
                                    <i class='fas fa-edit'></i> Editar
                                </a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>

            </table>
        </div>
        <?php $conn->close(); ?>
        <!-- Modal de prueba -->
        <!-- Modal -->
    </body>

</html>