<?php
include ("config.php"); // Asegúrate de que este archivo tenga la conexión a la base de datos

if (isset($_GET['caseta'])) {
    $casetaId = intval($_GET['caseta']);

    // Eliminar los registros de los corrales asociados a la caseta
    $query_corrales = "DELETE FROM corrales WHERE caseta_id = $casetaId";
    $resultado_corrales = mysqli_query($conexion, $query_corrales);

    // Actualizar la caseta para poner todos los campos en NULL excepto el ID y el nombre
    $query_caseta = "UPDATE casetas SET 
                        num_cerdos = NULL,
                        peso_promedio = NULL,
                        edad_promedio = NULL,
                        fecha_llegada = NULL,
                        etapa_alimentacion = NULL,
                        creado_en = NULL
                     WHERE id = $casetaId";
    $resultado_caseta = mysqli_query($conexion, $query_caseta);

    // Verificar si ambas operaciones fueron exitosas
    if ($resultado_corrales && $resultado_caseta) {
        session_start();

         // Obtener el nombre de usuario desde la sesión
        $usuario = $_SESSION['nombre'];

        // Insertar registro en la tabla historial
        $accion = "Vació la caseta con ID: $casetaId";
        $fecha_hora = date('Y-m-d H:i:s');
        $registro = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES ('$accion', '$fecha_hora', '$usuario')";
        mysqli_query($conexion, $registro);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al limpiar la caseta.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de caseta no proporcionado.']);
}
?>
