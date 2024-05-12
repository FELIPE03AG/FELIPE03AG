<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");



 



// Limpiar el buffer de salida, si es necesario
ob_end_flush();
?>