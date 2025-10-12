<?php
ob_start();
include("config.php");
session_start(); // Iniciar sesión al comienzo

$u = isset($_POST['u']) ? trim($_POST['u']) : NULL;
$c = isset($_POST['c']) ? trim($_POST['c']) : NULL;

// Verificar si se enviaron los datos
if (empty($u) || empty($c)) {
    header("Location: index.php?valor=1");
    exit();
}

// Primero, verificar si el usuario existe
$consultaUsuario = mysqli_prepare($conexion, "SELECT * FROM usuarios WHERE u = ?");
mysqli_stmt_bind_param($consultaUsuario, "s", $u);
mysqli_stmt_execute($consultaUsuario);
$resultadoUsuario = mysqli_stmt_get_result($consultaUsuario);

if ($fila = mysqli_fetch_assoc($resultadoUsuario)) {
    // Usuario encontrado, ahora verificar contraseña
    $claveEncriptada = $fila['c']; // Campo de la contraseña en la BD (SHA1)
    if (sha1($c) === $claveEncriptada) {
        // Contraseña correcta → iniciar sesión
        $_SESSION['id'] = $fila['id'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['rol'] = $fila['rol'];

        header("Location: cerdos.php");
        exit();
    } else {
        // Contraseña incorrecta
        header("Location: index.php?valor=5");
        exit();
    }
} else {
    // Usuario no existe
    header("Location: index.php?valor=4");
    exit();
}

ob_end_flush();
?>