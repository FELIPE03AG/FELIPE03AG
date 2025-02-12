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
$sql = "SELECT id, u, nombre, co, rol FROM usuarios";
$result = $conexion->query($sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>Perfil de Usuario</title>
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
            z-index: 1000;
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
            <div>
         <div class="user-name">
    <?= htmlspecialchars($nombre) ?>
</div>
         </div>
        </div>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Content -->
        <div class="content">
            
            <h2>Usuarios Registrados</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
        </tr>
        
        <?php
        // 3️⃣ Verificar si hay datos en la tabla
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['u']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['co']}</td>
                        <td>{$row['rol']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No hay usuarios registrados</td></tr>";
        }
        $conexion->close();
        ?>
    </table>

        </div>


    <h2 class="text-center">Gestión de Usuarios</h2>

<!-- Botón para abrir modal de agregar usuario -->
<button class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar Usuario</button>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT id, u, nombre, co, rol FROM usuarios";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['u']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['co']}</td>
                    <td>{$row['rol']}</td>
                    <td>
                        <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#modalEditar'
                            data-id='{$row['id']}' data-usuario='{$row['u']}'
                            data-nombre='{$row['nombre']}' data-correo='{$row['co']}'
                            data-rol='{$row['rol']}'>Editar</button>
                        
                        <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modalEliminar'
                            data-id='{$row['id']}'>Eliminar</button>
                    </td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

<?php $conn->close(); ?>

<!-- Modales -->
<?php include 'modales.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Llenar modal de edición con los datos del usuario
    document.getElementById('modalEditar').addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        document.getElementById('editId').value = button.getAttribute('data-id');
        document.getElementById('editUsuario').value = button.getAttribute('data-usuario');
        document.getElementById('editNombre').value = button.getAttribute('data-nombre');
        document.getElementById('editCorreo').value = button.getAttribute('data-correo');
        document.getElementById('editRol').value = button.getAttribute('data-rol');
    });

    // Llenar modal de eliminación con el ID del usuario
    document.getElementById('modalEliminar').addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        document.getElementById('deleteId').value = button.getAttribute('data-id');
    });
</script>








    
    
</body>
</html>