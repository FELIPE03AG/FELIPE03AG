<?php
// Incluir configuración para la conexión a la base de datos
include("config.php");

// Recibir los datos del formulario
$cantidad = isset($_POST['num_cerdos']) ? $_POST['num_cerdos'] : '';
$caseta = isset($_POST['num_caseta']) ? $_POST['num_caseta'] : '';
$fecha = isset($_POST['fecha_llegada_cerdos']) ? $_POST['fecha_llegada_cerdos'] : '';
$peso = isset($_POST['peso_prom']) ? $_POST['peso_prom'] : '';
$edad = isset($_POST['edad_prom']) ? $_POST['edad_prom'] : '';
$etapa = isset($_POST['etapa_inicial']) ? $_POST['etapa_inicial'] : '';

// Verificar si el número de caseta ya está ocupado
$query = "SELECT COUNT(*) as count FROM cerdos WHERE num_caseta = '$caseta'";
$result = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($result);
$caseta_existente = $row['count'] > 0;

if ($caseta_existente) {
    // Si el número de caseta ya está ocupado, redireccionar a la página anterior mostrando el mensaje de error
    header("Location: cerdos.php?error=caseta_existente");
    exit();
} else {
    // Insertar los datos en la base de datos
    $consulta = "INSERT INTO cerdos (num_cerdos, num_caseta, fecha_llegada_cerdos, peso_prom, edad_prom, etapa_inicial) 
                 VALUES (?, ?, ?, ?, ?, ?)";
    $intenta = $conexion->prepare($consulta);
    $intenta->bind_param("isssis", $cantidad, $caseta, $fecha, $peso, $edad, $etapa);

    if ($intenta->execute()) {
        // La consulta se realizó con éxito, redireccionar a la página de cerdos
        header("Location: cerdos.php");
        exit();
    } else {
        // Error al ejecutar la consulta, redireccionar a la página anterior mostrando el mensaje de error
        header("Location: cerdos.php?error=error_ejecutar_consulta");
        exit();
    }
}

// Cerrar la conexión
$conexion->close();
?>