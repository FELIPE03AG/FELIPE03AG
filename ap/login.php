<?php
ob_start();
include("config.php");

$u = isset($_REQUEST['u']) ? $_REQUEST['u'] : NULL;
$c = isset($_REQUEST['c']) ? $_REQUEST['c'] : NULL;

echo $c, "</br>";
echo $u, "</br>";

session_start(); // Iniciar sesión al comienzo

// Verificar si el usuario existe
$consulta = mysqli_prepare($conexion, "SELECT * FROM usuarios WHERE u = ? AND c = SHA1(?)");
mysqli_stmt_bind_param($consulta, "ss", $u, $c);
mysqli_stmt_execute($consulta);
$resultado = mysqli_stmt_get_result($consulta);

if ($fila = mysqli_fetch_assoc($resultado)) {
    // Usuario y contraseña correctos
    $_SESSION['id'] = $fila['id'];
    $_SESSION['nombre'] = $fila['nombre'];
    $_SESSION['rol'] = $fila['rol'];

    header("Location: principal.php");
    exit();
} else {
    // Usuario o contraseña incorrectos
    header("Location: index.php?valor=1");
    exit();
}

ob_end_flush();
?>