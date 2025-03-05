<?php
include("config.php");
session_start();

if (!isset($_POST['id'])) {
    die("Error: No se recibió un ID válido.");
}

$id = $_POST['id'];
$usuario = $_POST['usuario'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$rol = $_POST['rol'];
$password = $_POST['password'];

// Si el campo de contraseña no está vacío, actualizarla con SHA1
if (!empty($password)) {
    $password = sha1($password);
    $sql = "UPDATE usuarios SET u=?, nombre=?, co=?, rol=?, c=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $usuario, $nombre, $correo, $rol, $password, $id);
} else {
    // Si no se proporciona una nueva contraseña, no actualizarla
    $sql = "UPDATE usuarios SET u=?, nombre=?, co=?, rol=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $usuario, $nombre, $correo, $rol, $id);
}

if ($stmt->execute()) {
    echo "<script>window.location='administrar_usuarios.php';</script>";
} else {
    echo "<script>alert('Error al actualizar usuario.'); window.history.back();</script>";
}

$stmt->close();
$conexion->close();
?>
