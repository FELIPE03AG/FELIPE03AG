<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Perfil de Usuarios</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/style_principal.css">
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
</head>

<body>

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
    <div class="navbar d-flex justify-content-between align-items-center px-4 py-2 bg-light shadow">
        <h1 class="mb-0">GestAP</h1>

        <!-- Usuario sin dropdown -->
        <div class="d-flex align-items-center">
            <i class="fas fa-user-circle me-2"></i>
            <span><?= htmlspecialchars($nombre) ?></span>
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
                        <button type="submit" class="btn btn-primary">Agregar Usuario</button>
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
    function abrirConfirmacion() {
        var usuarioSeleccionado = document.getElementById("deleteUserName").value;
        if (!usuarioSeleccionado) {
            alert("Por favor, selecciona un usuario para eliminar.");
            return;
        }
        
        document.getElementById("usuarioAEliminar").textContent = usuarioSeleccionado;
        document.getElementById("usuarioInput").value = usuarioSeleccionado;

        // Mostrar el modal de confirmación
        var confirmModal = new bootstrap.Modal(document.getElementById("confirmDeleteModal"));
        confirmModal.show();
}

    </script>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Content -->



        <div class="content">
            <h2>Administrar Usuarios</h2>
        
            <button class="btn btn-success rounded-circle has-tooltip"
                    data-bs-toggle="modal"
                    data-bs-target="#addUserModal"
                    title="Añadir Usuario"
                    type="button">
                <i class="fas fa-plus"></i>Añadir
            </button>

            <button class="btn btn-danger rounded-circle has-tooltip" 
            data-bs-toggle="modal" 
            data-bs-target="#deleteUserModal"
            title="Eliminar Usuario"
            type="button">
            <i class="fas fa-trash"></i>Eliminar
            </button>

            <h2></h2>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
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
                            <td>{$row['u']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['co']}</td>
                            <td>{$row['rol']}</td>
                            <td class='text-center'>
                                <a href='editar_usuario.php?id={$row['id']}'
                                class='btn btn-primary rounded-circle'
                                data-bs-toggle='tooltip'
                                data-bs-placement='top'
                                title='Editar Usuario'>
                                <i class='fas fa-edit'></i>
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

    <script>
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>

    </body>

</html>