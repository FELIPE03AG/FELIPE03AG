<?php

// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos

include("config.php");


// Verificar si se recibió el identificador del registro a eliminar
if(isset($_POST['id_registro'])) {
    // Obtener el identificador del registro desde la solicitud POST
    $id_registro = $_POST['id_registro'];

    // Preparar la consulta para eliminar el registro de la base de datos
    $query = "DELETE FROM cerdos WHERE id_registro = ?";

    // Preparar la declaración SQL
    $stmt = $conexion->prepare($query);

    // Vincular parámetro
    $stmt->bind_param("i", $id_registro);

    // Ejecutar la consulta
    if($stmt->execute()) {
        // Si la eliminación fue exitosa, enviar una respuesta de éxito
        echo "El registro se eliminó correctamente.";
    } else {
        // Si hubo un error al eliminar el registro, enviar un mensaje de error
        echo "Error al eliminar el registro: " . $conexion->error;
    }

    // Cerrar la declaración preparada
    $stmt->close();
} else {
    // Si no se recibió el identificador del registro a eliminar, enviar un mensaje de error
    echo "Error: Identificador de registro no recibido.";
}


// Limpiar el buffer de salida, si es necesario
ob_end_flush();
?>