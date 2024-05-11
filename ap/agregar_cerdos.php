<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");

//estos datos los estoy agarrando del modal de cerdos.php
$cantidad = isset($_REQUEST['num_cerdos']) ? trim($_REQUEST['num_cerdos']) : NULL;
$caseta = isset($_REQUEST['num_caseta']) ? trim($_REQUEST['num_caseta']) : NULL;
$fecha = isset($_REQUEST['fecha_llegada_cerdos']) ? trim($_REQUEST['fecha_llegada_cerdos']) : NULL;
$peso = isset($_REQUEST['peso_prom']) ? trim($_REQUEST['peso_prom']) : NULL;
$edad = isset($_REQUEST['edad_prom']) ? trim($_REQUEST['edad_prom']) : NULL;
$etapa = isset($_REQUEST['etapa_inicial']) ? trim($_REQUEST['etapa_inicial']) : NULL;

$consulta = "INSERT INTO cerdos (num_cerdos, num_caseta, fecha_llegada_cerdos, peso_prom, edad_prom, etapa_inicial) 
VALUES (?, ?, ?, ?, ?, ?)";

$intenta = $conexion->prepare($consulta);
$intenta->bind_param("isssis", $cantidad, $caseta, $fecha, $peso, $edad, $etapa);

 // Insertar en la base de datos
if ($intenta->execute()) {
  // La consulta se realizó con éxito
  echo "La consulta se realizó correctamente.";
  header("location:index.php");
} else {
  // Error al ejecutar la consulta
  echo "Error al ejecutar la consulta: " . $conexion->error;
}

// Cerrar la conexión
$conexion->close();



 



// Limpiar el buffer de salida, si es necesario
ob_end_flush();
?>