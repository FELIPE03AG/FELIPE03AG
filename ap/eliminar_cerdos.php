<?php
// Incluir configuración para la conexión a la base de datos
include("config.php");

// Verificar si se recibió el identificador del registro a eliminar
if(isset($_POST['id_registro'])) {
    // Obtener el identificador del registro desde la solicitud POST
    $id_registro = $_POST['id_registro'];

    // Obtener los detalles del registro antes de eliminarlo
    $query_registro = "SELECT * FROM cerdos WHERE id_registro = ?";
    $stmt_registro = $conexion->prepare($query_registro);
    $stmt_registro->bind_param("i", $id_registro);
    $stmt_registro->execute();
    $registro = $stmt_registro->get_result()->fetch_assoc();
    $stmt_registro->close();

    // Preparar la consulta para eliminar el registro de la base de datos
    $query = "DELETE FROM cerdos WHERE id_registro = ?";

    // Preparar la declaración SQL
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_registro);

    // Ejecutar la consulta
    if($stmt->execute()) {
        // Registro en la tabla de historial
        session_start();
        $usuario = $_SESSION['u'];
        $accion = "Eliminó un registro en la tabla de cerdos";
        $fecha_hora = date('Y-m-d H:i:s');
        $registro_historial = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES (?, ?, ?)";
        $stmt_historial = $conexion->prepare($registro_historial);
        $stmt_historial->bind_param("sss", $accion, $fecha_hora, $usuario);
        $stmt_historial->execute();
        $stmt_historial->close();
        
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

// Cerrar la conexión
$conexion->close();
?>
