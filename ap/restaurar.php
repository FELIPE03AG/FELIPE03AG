<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");


$newPassword = isset($_REQUEST['newPassword']) ? trim($_REQUEST['newPassword']) : NULL;
$confirmPassword = isset($_REQUEST['confirmPassword']) ? trim($_REQUEST['confirmPassword']) : NULL;







echo $newPassword, "</br>";
echo $confirmPassword, "</br>";
$idu = isset($_POST['idu']) ? $_POST['idu'] : null;
echo "El valor de idu es: " . htmlspecialchars($idu, ENT_QUOTES, 'UTF-8');


// Consulta preparada para actualizar la contraseña con SHA-1 (no recomendado para seguridad)
$sql = "UPDATE `usuarios` SET `c` = SHA1(?) WHERE `id` = ?";
// Preparar la consulta
$stmt = $conexion->prepare($sql);

if ($stmt) {
    // Asignar valores a los parámetros
    $stmt->bind_param("si", $newPassword, $idu);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Contraseña actualizada exitosamente.";
        header("location:index.php");

    } else {
        echo "Error al actualizar la contraseña: " . $stmt->error;
        
    }

    // Cerrar la consulta
    $stmt->close();
} else {
    echo "Error al preparar la consulta: " . $conexion->error;
}



// Cerrar la conexión
$conexion->close();



 



// Limpiar el buffer de salida, si es necesario
ob_end_flush();
?>