<?php
// Iniciar sesión para verificar si el usuario está autenticado
session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}


// Incluir la configuración de la base de datos
include("config.php");

// Verificar si se ha recibido el ID por POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Asegurar que el ID sea un número entero

    // Preparar la consulta para eliminar el usuario
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id); // "i" indica que el parámetro es un entero
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el usuario.";
            $_SESSION['tipo_mensaje'] = "danger";
        }
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error en la preparación de la consulta.";
        $_SESSION['tipo_mensaje'] = "danger";
    }

    // Redirigir de nuevo a la página de usuarios
    header("Location: administrar_usuarios.php");
    exit();
} else {
    $_SESSION['mensaje'] = "ID no recibido.";
    $_SESSION['tipo_mensaje'] = "danger";
    header("Location: administrar_usuarios.php");
    exit();
}
?>
