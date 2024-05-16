<?php
// Incluir configuración para la conexión a la base de datos
include("config.php");

// Verificar si se recibió el ID del registro
if(isset($_POST['id_registro'])) {
    // Sanitizar el ID del registro
    $id_registro = mysqli_real_escape_string($conexion, $_POST['id_registro']);

    // Consulta para obtener los detalles del registro
    $query = "SELECT * FROM cerdos WHERE id_registro = '$id_registro'";
    $resultado = mysqli_query($conexion, $query);

    // Verificar si se encontraron resultados
    if(mysqli_num_rows($resultado) > 0) {
        // Mostrar los detalles del registro
        $fila = mysqli_fetch_assoc($resultado);
        echo "<p><strong>Número de Caseta:</strong> ".$fila['num_caseta']."</p>";
        echo "<p><strong>Cantidad de Cerdos:</strong> ".$fila['num_cerdos']."</p>";
        echo "<p><strong>Fecha de Llegada:</strong> ".$fila['fecha_llegada_cerdos']."</p>";
        echo "<p><strong>Peso Promedio (kg):</strong> ".$fila['peso_prom']."</p>";
        echo "<p><strong>Edad Promedio (Semanas):</strong> ".$fila['edad_prom']."</p>";
        echo "<p><strong>Etapa de Alimentación:</strong> ".$fila['etapa_inicial']."</p>";
    } else {
        // Mostrar un mensaje si no se encontraron detalles del registro
        echo "No se encontraron detalles del registro.";
    }
} else {
    // Mostrar un mensaje de error si no se recibió el ID del registro
    echo "Error: No se recibió el ID del registro.";
}
?>