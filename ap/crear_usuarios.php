<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $rol = trim($_POST['rol']);
    $password = sha1($_POST['password']); // Encripta la contraseña con SHA-1

    if (empty($usuario) || empty($nombre) || empty($correo) || empty($rol) || empty($_POST['password'])) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
        $_SESSION['tipo_mensaje'] = "danger";
    } else {
        $sql = "INSERT INTO usuarios (u, nombre, co, rol, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssss", $usuario, $nombre, $correo, $rol, $password);
            if ($stmt->execute()) {
                $_SESSION['mensaje'] = "Usuario creado correctamente.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al crear el usuario.";
                $_SESSION['tipo_mensaje'] = "danger";
            }
            $stmt->close();
        } else {
            $_SESSION['mensaje'] = "Error en la consulta.";
            $_SESSION['tipo_mensaje'] = "danger";
        }
    }
}

header("Location: administrar_usuarios.php");
exit();
?>
