<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");

// Verificar si se recibió un ID válido del registro a eliminar
if(isset($_POST['id'])) {
    // Sanitizar el ID del registro
    $id = mysqli_real_escape_string($conexion, $_POST['id']);

    // Consulta para eliminar el registro de la base de datos
    $sql = "DELETE FROM cerdos WHERE id_registro = '$id'";

    // Ejecutar la consulta
    if(mysqli_query($conexion, $sql)) {
        // Si la eliminación fue exitosa, devolver una respuesta exitosa
        echo "El registro se eliminó correctamente.";
    } else {
        // Si hubo un error al ejecutar la consulta, devolver un mensaje de error
        echo "Error al eliminar el registro: " . mysqli_error($conexion);
    }
} else {
    // Si no se recibió un ID válido, devolver un mensaje de error
    echo "ID de registro no válido.";
}



// Limpiar el buffer de salida, si es necesario
ob_end_flush();
?>