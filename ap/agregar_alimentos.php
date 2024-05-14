<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");

//estos datos los estoy agarrando del modal de cerdos.php
$tolva = isset($_REQUEST['num_tolva']) ? trim($_REQUEST['num_tolva']) : NULL;
$cantidad = isset($_REQUEST['cantidad_alim']) ? trim($_REQUEST['cantidad_alim']) : NULL;
$fecha = isset($_REQUEST['fecha_llegada_alim']) ? trim($_REQUEST['fecha_llegada_alim']) : NULL;
$etapa = isset($_REQUEST['etapa_alim']) ? trim($_REQUEST['etapa_alim']) : NULL;

$consulta = "INSERT INTO alimentacion (num_tolva, cantidad_alim, fecha_llegada_alim, etapa_alim) 
VALUES (?, ?, ?, ?)";

$intenta = $conexion->prepare($consulta);
$intenta->bind_param("isss", $tolva, $cantidad, $fecha, $etapa);

 // Insertar en la base de datos
if ($intenta->execute()) {
  // La consulta se realizó con éxito
  echo "La consulta se realizó correctamente.";
  header("location:alimentos.php");
} else {
  // Error al ejecutar la consulta
  echo "Error al ejecutar la consulta: " . $conexion->error;
}





 



// Limpiar el buffer de salida, si es necesario
ob_end_flush();
?>