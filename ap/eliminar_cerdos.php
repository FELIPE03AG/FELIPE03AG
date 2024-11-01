<?php
// Incluir configuración para la conexión a la base de datos
include("config.php");

// Verificar si se recibió el identificador del registro a eliminar
if (isset($_POST['id_registro'])) {
    // Obtener el identificador del registro desde la solicitud POST
    $id_registro = $_POST['id_registro'];

    // Obtener los detalles del registro de cerdos antes de eliminarlo
    $query_registro = "SELECT num_caseta FROM cerdos WHERE id_registro = ?";
    $stmt_registro = $conexion->prepare($query_registro);
    $stmt_registro->bind_param("i", $id_registro);
    $stmt_registro->execute();
    $registro = $stmt_registro->get_result()->fetch_assoc();
    $num_caseta = $registro['num_caseta'];
    $stmt_registro->close();

    // Iniciar la transacción
    mysqli_begin_transaction($conexion);

    try {
        // Preparar la consulta para eliminar los corrales de esa caseta
        $queryCorrales = "DELETE FROM corrales WHERE num_caseta = ?";
        $stmtCorrales = $conexion->prepare($queryCorrales);
        $stmtCorrales->bind_param("i", $num_caseta);
        $stmtCorrales->execute();
        $stmtCorrales->close();

        // Preparar la consulta para eliminar el registro de cerdos
        $queryCerdos = "DELETE FROM cerdos WHERE id_registro = ?";
        $stmtCerdos = $conexion->prepare($queryCerdos);
        $stmtCerdos->bind_param("i", $id_registro);

        // Ejecutar la consulta para eliminar cerdos
        if ($stmtCerdos->execute()) {
            // Confirmar la transacción
            mysqli_commit($conexion);

            // Registro en la tabla de historial
            session_start();
            $usuario = $_SESSION['nombre']; // Cambiar de $_SESSION['u'] a $_SESSION['nombre']
            $accion = "Eliminó un registro en la tabla de cerdos y sus corrales asociados.";
            $fecha_hora = date('Y-m-d H:i:s');
            $registro_historial = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES (?, ?, ?)";
            $stmt_historial = $conexion->prepare($registro_historial);
            $stmt_historial->bind_param("sss", $accion, $fecha_hora, $usuario);
            $stmt_historial->execute();
            $stmt_historial->close();

            // Si la eliminación fue exitosa, enviar una respuesta de éxito
            echo "El registro y sus corrales se eliminaron correctamente.";
        } else {
            // Si hubo un error al eliminar el registro de cerdos, revertir la transacción
            mysqli_rollback($conexion);
            echo "Error al eliminar el registro de cerdos: " . $conexion->error;
        }

        // Cerrar la declaración preparada
        $stmtCerdos->close();
    } catch (Exception $e) {
        // Si hay un error, revertir la transacción
        mysqli_rollback($conexion);
        echo "Error: " . $e->getMessage();
    }
} else {
    // Si no se recibió el identificador del registro a eliminar, enviar un mensaje de error
    echo "Error: Identificador de registro no recibido.";
}

// Cerrar la conexión
$conexion->close();
?>
