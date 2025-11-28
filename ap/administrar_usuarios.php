<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Perfil de Usuarios</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="styles/style_navbar.css">
    <link rel="stylesheet" href="styles/style_sidebar.css">
    <link rel="stylesheet" href="styles/style_cerdos.css">
</head>

<body>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarLinks = document.querySelectorAll(".sidebar a");
            const currentPath = window.location.pathname.split("/").pop(); // Obtiene el archivo actual

            // Configura las páginas relacionadas para cada enlace
            const relatedPages = {
                "administrar_usuarios.php": ["administrar_usuarios.php", "perfil.php", "perfil1.php"]

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

        // Función para mostrar mensajes de usuario mediante un modal genérico
        function showMessageModal(title, body, isError = false) {
            const modalTitle = document.getElementById('messageModalLabel');
            const modalBody = document.getElementById('messageModalBody');
            const modalHeader = document.querySelector('#messageModal .modal-header');
            
            modalTitle.innerHTML = `<i class="fas ${isError ? 'fa-times-circle' : 'fa-check-circle'} me-2"></i> ${title}`;
            modalBody.textContent = body;
            
            // Cambiar el color del encabezado
            modalHeader.classList.remove('bg-danger', 'bg-success');
            if (isError) {
                modalHeader.classList.add('bg-danger', 'text-white');
            } else {
                modalHeader.classList.add('bg-success', 'text-white');
            }

            var messageModal = new bootstrap.Modal(document.getElementById('messageModal'), {});
            messageModal.show();
        }
    </script>

    <?php include 'navbar.php'; ?>

    <?php include 'sidebar.php'; ?>

    
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

    <script>
    function cargarUsuario(id) {
        // Hacer una solicitud AJAX para obtener los datos del usuario
        fetch(`obtener_usuario.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                // Notar que los IDs dentro del modal de agregar (addUserModal) 
                // pueden entrar en conflicto si no se usa un modal de edición separado
                // o se cambian los IDs aquí. Por simplicidad, se mantiene
                // el script original sin un modal de edición visible.
                console.log("Datos de usuario cargados, se asume que existe un modal de edición.");
            })
            .catch(error => console.error('Error al cargar usuario:', error));
    }

    // Manejar el envío del formulario de edición (se asume que existe #formEditarUsuario)
    const formEditarUsuario = document.getElementById('formEditarUsuario');
    if (formEditarUsuario) {
        formEditarUsuario.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('editar_usuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                // Reemplazado alert(result) con modal
                showMessageModal("Actualización", result);
                location.reload(); // Recargar la página tras la edición
            })
            .catch(error => console.error('Error en la actualización:', error));
        });
    }
    </script>


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


    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al usuario <strong id="usuarioAEliminar"></strong>?</p>
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

    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Mensaje</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="messageModalBody">
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="errorAdminModal" tabindex="-1" aria-labelledby="errorAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorAdminModalLabel"><i class="fas fa-exclamation-triangle"></i> <?php echo isset($_SESSION['modal_error_title']) ? htmlspecialchars($_SESSION['modal_error_title']) : 'Error de Seguridad'; ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><?php echo isset($_SESSION['modal_error_body']) ? htmlspecialchars($_SESSION['modal_error_body']) : 'No es posible realizar esta acción por una restricción de seguridad.'; ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function abrirConfirmacion() {
            var usuarioSeleccionado = document.getElementById("deleteUserName").value;
            if (!usuarioSeleccionado) {
                // Reemplazado alert() con modal
                showMessageModal("Error", "Por favor, selecciona un usuario para eliminar.", true);
                return;
            }
            
            // Cerrar el modal de selección de usuario (deleteUserModal)
            var deleteModalEl = document.getElementById('deleteUserModal');
            var deleteModal = bootstrap.Modal.getInstance(deleteModalEl); 
            if (deleteModal) {
                 deleteModal.hide();
            }


            document.getElementById("usuarioAEliminar").textContent = usuarioSeleccionado;
            document.getElementById("usuarioInput").value = usuarioSeleccionado;

            // Mostrar el modal de confirmación
            var confirmModal = new bootstrap.Modal(document.getElementById("confirmDeleteModal"));
            confirmModal.show();
        }

    </script>

    <div class="content">
        <h2>Administrar Usuarios</h2>
        <div class="mb-3 d-flex justify-content-center gap-2">
            <button class="btn btn-success rounded-circle" 
                    style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"
                    data-bs-toggle="modal" 
                    data-bs-target="#addUserModal"
                    data-bs-placement="top" 
                    data-bs-title="Añadir Usuario">
                <i class="fas fa-plus"></i>
            </button>

            <button class="btn btn-danger rounded-circle" 
                    style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteUserModal"
                    data-bs-placement="top" 
                    data-bs-title="Eliminar Usuario">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-title]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                // Inicializa el tooltip para los botones circulares
                return new bootstrap.Tooltip(tooltipTriggerEl) 
            })
        });
        </script>

        <style>
        .tooltip-inner {
            background-color: black !important; /* Fondo negro */
            color: white !important; /* Texto blanco */
            font-weight: bold;
        }
        .tooltip.bs-tooltip-top .tooltip-arrow::before {
            border-top-color: black !important; /* Flecha negra */
        }
        </style>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Editar</th>
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

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
    
    <?php
    // --- Lógica para mostrar el Modal de Error Admin (Específico) ---
    if (isset($_SESSION['show_modal']) && $_SESSION['show_modal'] === true) {
        // El script se ejecutará al cargar la página y mostrará el modal
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', function() {";
        echo "  var errorModal = new bootstrap.Modal(document.getElementById('errorAdminModal'), {});";
        echo "  errorModal.show();";
        echo "});";
        echo "</script>";
        
        // Limpiar las variables de sesión para que el modal no vuelva a aparecer
        unset($_SESSION['show_modal']);
        unset($_SESSION['modal_error_title']);
        unset($_SESSION['modal_error_body']);
    }

    // --- Lógica para mostrar mensajes genéricos de éxito/error ---
    if (isset($_SESSION['mensaje_exito'])) {
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', function() {";
        echo "  showMessageModal('Éxito', '" . htmlspecialchars($_SESSION['mensaje_exito']) . "', false);";
        echo "});";
        echo "</script>";
        unset($_SESSION['mensaje_exito']);
    }

    if (isset($_SESSION['mensaje_error'])) {
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', function() {";
        echo "  showMessageModal('Error', '" . htmlspecialchars($_SESSION['mensaje_error']) . "', true);";
        echo "});";
        echo "</script>";
        unset($_SESSION['mensaje_error']);
    }
    ?>

<?php if (isset($_GET['success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Usuario registrado!',
    text: 'La información fue almacenada correctamente.',
    showConfirmButton: false,
    timer: 2000
}).then(() => {
    window.location.href = "administrar_usuarios.php";
});
</script>
<?php endif; ?>

</body>
</html>