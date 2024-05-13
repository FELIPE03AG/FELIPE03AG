<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");

// Verificar si se recibieron los datos del registro a editar
if(isset($_POST['id_registro'], $_POST['cantidad'], $_POST['caseta'], $_POST['fecha'], $_POST['peso'], $_POST['edad'], $_POST['etapa'])) {
    // Obtener los datos del formulario
    $id_registro = $_POST['id_registro'];
    $cantidad = $_POST['cantidad'];
    $caseta = $_POST['caseta'];
    $fecha = $_POST['fecha'];
    $peso = $_POST['peso'];
    $edad = $_POST['edad'];
    $etapa = $_POST['etapa'];

    // Preparar la consulta para actualizar el registro en la base de datos
    $query = "UPDATE cerdos SET num_cerdos = ?, num_caseta = ?, fecha_llegada_cerdos = ?, peso_prom = ?, edad_prom = ?, etapa_inicial = ? WHERE id_registro = ?";

    // Preparar la declaración SQL
    $stmt = $conexion->prepare($query);

    // Vincular parámetros
    $stmt->bind_param("iissssi", $cantidad, $caseta, $fecha, $peso, $edad, $etapa, $id_registro);

    // Ejecutar la consulta
    if($stmt->execute()) {
        // Si la actualización fue exitosa, enviar una respuesta de éxito
        echo "El registro se actualizó correctamente.";
    } else {
        // Si hubo un error al actualizar el registro, enviar un mensaje de error
        echo "Error al actualizar el registro: " . $conexion->error;
    }

    // Cerrar la declaración preparada
    $stmt->close();
} else {
    // Si no se recibieron todos los datos del formulario, enviar un mensaje de error
    echo "Error: Todos los campos son requeridos.";
}

 



// Limpiar el buffer de salida, si es necesario
ob_end_flush();
?>