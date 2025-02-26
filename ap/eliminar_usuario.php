<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}
include("config.php"); // Conexión a la base de datos

// Verificar si se ha enviado el ID del usuario a eliminar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Convertir a entero para evitar inyecciones SQL

    // Verificar que el ID no sea vacío o 0
    if ($id > 0) {
        // Preparar la consulta SQL para eliminar el usuario
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta y verificar si fue exitosa
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el usuario.";
            $_SESSION['tipo_mensaje'] = "danger";
        }

        // Cerrar la conexión
        $stmt->close();
        $conexion->close();
    } else {
        $_SESSION['mensaje'] = "Nombre de Usuario Valido.";
        $_SESSION['tipo_mensaje'] = "warning";
    }
} else {
    $_SESSION['mensaje'] = "Acceso no autorizado.";
    $_SESSION['tipo_mensaje'] = "danger";
}

// Redirigir a la página de usuarios después de la operación
header("Location: administrar_usuarios.php");
exit();
?>
