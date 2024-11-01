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
try{


// Insertar en la tabla cerdos
$consultaCerdos = "INSERT INTO cerdos (num_cerdos, num_caseta, fecha_llegada_cerdos, peso_prom, edad_prom, etapa_inicial) 
                   VALUES (?, ?, ?, ?, ?, ?)";
$stmtCerdos = $conexion->prepare($consultaCerdos);
$stmtCerdos->bind_param("isssis", $cantidad, $caseta, $fecha, $peso, $edad, $etapa);

if ($stmtCerdos->execute()) {
    // Obtener el id del último registro insertado
    $idCerdos = $conexion->insert_id;

    // Distribuir los cerdos en los corrales
    $corrales_a_usar = 28;
    $cerdos_por_corral = floor($cantidad / $corrales_a_usar);
    $sobrante = $cantidad % $corrales_a_usar;

    for ($i = 2; $i < 30; $i++) { // Corrales del 2 al 29
        $numCerdosCorral = $cerdos_por_corral + ($sobrante-- > 0 ? 1 : 0);
        $consultaCorrales = "INSERT INTO corrales (num_caseta, num_corral, num_cerdos) VALUES (?, ?, ?)";
        $stmtCorrales = $conexion->prepare($consultaCorrales);
        $stmtCorrales->bind_param("iii", $idCerdos, $i, $numCerdosCorral);
        $stmtCorrales->execute();
    }

    // Registro en la tabla de historial
    session_start();
    $usuario = $_SESSION['nombre'];
    $accion = "Agregó un nuevo registro en la tabla de cerdos";
    $fecha_hora = date('Y-m-d H:i:s');
    $registro = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES ('$accion', '$fecha_hora', '$usuario')";
    mysqli_query($conexion, $registro);

    // La consulta se realizó con éxito, redireccionar a la página de cerdos
    header("Location: cerdos.php");
    exit();
} else {
    // Error al ejecutar la consulta, redireccionar a la página anterior
    header("Location: cerdos.php?error=error_ejecutar_consulta");
    exit();
}

// Cerrar la conexión
$conexion->close();
}catch(Exception $e)
{
    echo $e;
}
?>



