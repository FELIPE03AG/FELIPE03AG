<?php
session_start();

include("config.php");



   



$usuario = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

// Obtener los datos del formulario
$caseta = $_POST['caseta'];
$num_cerdos = $_POST['num_cerdos'];
$peso_prom = $_POST['peso_prom'];
$edad_prom = $_POST['edad_prom'];
$fecha_llegada = $_POST['fecha_llegada'];
$etapa = $_POST['etapa'];

// Verificar si la caseta ya existe en la base de datos
$query_verificar = "SELECT id FROM casetas WHERE nombre = 'Caseta $caseta'";
$resultado = $conexion->query($query_verificar);

if ($resultado->num_rows > 0) {
    // Si ya existe, obtenemos el ID de la caseta
    $fila = $resultado->fetch_assoc();
    $caseta_id = $fila['id'];

    // Actualizar la caseta existente
    $query_actualizar = "UPDATE casetas 
                         SET num_cerdos = $num_cerdos, peso_promedio = $peso_prom, edad_promedio = $edad_prom, fecha_llegada = '$fecha_llegada', etapa_alimentacion = '$etapa'
                         WHERE id = $caseta_id";
    $conexion->query($query_actualizar);
} else {
    // Si no existe, la insertamos y obtenemos el ID
    $query_insertar = "INSERT INTO casetas (nombre, num_cerdos, peso_promedio, edad_promedio, fecha_llegada, etapa_alimentacion) 
                       VALUES ('Caseta $caseta', $num_cerdos, $peso_prom, $edad_prom, '$fecha_llegada', '$etapa')";
    if ($conexion->query($query_insertar) === TRUE) {
        $caseta_id = $conexion->insert_id;
    } else {
        echo "Error al insertar la caseta: " . $conexion->error;
        exit();
    }
}

// Insertar los corrales asociados
for ($i = 1; $i <= 30; $i++) {
    $num_cerdos_corral = $_POST["corral_$i"];

    // Verificar si ya existe el corral
    $query_verificar_corral = "SELECT id FROM corrales WHERE numero_corral = $i AND caseta_id = $caseta_id";
    $resultado_corral = $conexion->query($query_verificar_corral);

    if ($resultado_corral->num_rows > 0) {
        // Actualizar el corral existente
        $query_actualizar_corral = "UPDATE corrales 
                                    SET num_cerdos = $num_cerdos_corral 
                                    WHERE numero_corral = $i AND caseta_id = $caseta_id";
        $conexion->query($query_actualizar_corral);


    } else {
        // Insertar un nuevo corral
        $query_corral = "INSERT INTO corrales (numero_corral, num_cerdos, caseta_id) 
                         VALUES ($i, $num_cerdos_corral, $caseta_id)";
        $conexion->query($query_corral);
    }
}

   // Guardar el registro en el historial
   $accion = "Agregó un nuevo registro en el Area de cerdos";
   $fecha_hora = date('Y-m-d H:i:s');
   $registro = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES ('$accion', '$fecha_hora', '$usuario')";
   mysqli_query($conexion, $registro);
  // Redirigir a la página de agregar cerdos
  header("Location: cerdos.php");
  
  exit(); // Importante para asegurar que no se ejecute nada después
$conexion->close();
?>
