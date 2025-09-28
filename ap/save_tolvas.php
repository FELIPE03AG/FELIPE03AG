<?php
session_start();
include("config.php");

$usuario = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

// Obtener datos del formulario
$tolva = $_POST['tolva'];
$num_alim = $_POST['num_alim'];
$fecha_alim = $_POST['fecha_alim'];
$etapa_alim = $_POST['etapa_alim'];

// Verificar si existe el registro en la tabla "alimentos" (no "tolvas")
$query_verificar = "SELECT id FROM alimentos WHERE nombre = 'Tolva $tolva'";
$resultado = $conexion->query($query_verificar);

if ($resultado->num_rows > 0) {
    // Si existe, actualizar
    $fila = $resultado->fetch_assoc();
    $tolva_id = $fila['id'];

    $query_actualizar = "UPDATE alimentos 
                         SET num_alim = $num_alim, fecha_alim = '$fecha_alim', etapa_alim = '$etapa_alim'
                         WHERE id = $tolva_id";
    $conexion->query($query_actualizar);
} else {
    // Si no existe, insertar nuevo registro
    $query_insertar = "INSERT INTO alimentos (nombre, num_alim, fecha_alim, etapa_alim) 
                       VALUES ('Tolva $tolva', $num_alim, '$fecha_alim', '$etapa_alim')";
    if ($conexion->query($query_insertar) === TRUE) {
        $tolva_id = $conexion->insert_id;
    } else {
        echo "Error al insertar en alimentos: " . $conexion->error;
        exit();
    }
}

// Registrar en el historial
$accion = "Agregó un nuevo registro en el Área de Alimentos";
$fecha_hora = date('Y-m-d H:i:s');
$registro = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES ('$accion', '$fecha_hora', '$usuario')";
mysqli_query($conexion, $registro);

// Cerrar conexión y redirigir
$conexion->close();
header("Location: alimentos.php");
exit();
?>