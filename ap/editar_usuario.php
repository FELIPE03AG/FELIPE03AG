<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $usuario = trim($_POST['usuario']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $rol = trim($_POST['rol']);
    $password = trim($_POST['password']); // Captura la nueva contraseña

    if (empty($usuario) || empty($nombre) || empty($correo) || empty($rol)) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios, excepto la contraseña.";
        $_SESSION['tipo_mensaje'] = "danger";
    } else {
        if (!empty($password)) {
            // Si el usuario ingresa una nueva contraseña, se encripta con SHA-1
            $password = sha1($password);
            $sql = "UPDATE usuarios SET u = ?, nombre = ?, co = ?, rol = ?, password = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssssi", $usuario, $nombre, $correo, $rol, $password, $id);
        } else {
            // Si no se cambia la contraseña, solo se actualizan los demás datos
            $sql = "UPDATE usuarios SET u = ?, nombre = ?, co = ?, rol = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssi", $usuario, $nombre, $correo, $rol, $id);
        }

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el usuario.";
            $_SESSION['tipo_mensaje'] = "danger";
        }

        $stmt->close();
    }
}

header("Location: administrar_usuarios.php");
exit();
?>
