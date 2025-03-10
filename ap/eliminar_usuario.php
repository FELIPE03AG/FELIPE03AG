<?php
// Incluir archivo de conexión a la base de datos
include("config.php"); // Asegúrate de que este archivo contiene los datos correctos para la conexión a la base de datos.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre de usuario del formulario
    $usuario_a_eliminar = trim($_POST['u']); // El 'u' es el nombre del input oculto del formulario que contiene el usuario seleccionado.

    // Validar que el nombre de usuario no esté vacío
    if (empty($usuario_a_eliminar)) {
        echo "El nombre de usuario es obligatorio.";
        exit();
    }

    // Preparar la consulta SQL para eliminar al usuario
    $sql = "DELETE FROM usuarios WHERE u = ?";

    // Usar prepared statements para evitar inyecciones SQL
    if ($stmt = $conexion->prepare($sql)) {
        // Vincular el parámetro
        $stmt->bind_param("s", $usuario_a_eliminar);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la eliminación fue exitosa, redirigir a la página de administrar usuarios
            echo "<script>window.location.href='administrar_usuarios.php';</script>";
        } else {
            echo "Error al eliminar el usuario: " . $stmt->error;
        }

        // Cerrar el statement
        $stmt->close();
    } else {
        echo "Error al preparar la consulta SQL.";
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
} else {
    echo "Acceso no autorizado.";
}
?>