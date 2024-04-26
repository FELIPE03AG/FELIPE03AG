<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");


$newPassword = isset($_REQUEST['newPassword']) ? trim($_REQUEST['newPassword']) : NULL;
$confirmPassword = isset($_REQUEST['confirmPassword']) ? trim($_REQUEST['confirmPassword']) : NULL;

echo $newPassword, "</br>";
echo $confirmPassword, "</br>";

 



// Limpiar el buffer de salida, si es necesario
ob_end_flush();