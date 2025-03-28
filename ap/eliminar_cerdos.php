<?php
session_start();
include("config.php");

$usuario = $_SESSION['nombre'];
$fecha_hora = date('Y-m-d H:i:s');

if ($_POST['tipo_eliminacion'] == 'muerte') {
    // Eliminación por Muerte
    $fecha_muerte = $_POST['fecha_muerte'];
    $num_caseta = $_POST['num_caseta_muerte'];
    $num_corral = $_POST['num_corral_muerte'];
    $causa_muerte = $_POST['causa_muerte'];

    // Consultar cuántos cerdos hay en el corral
    $query_verificar = "SELECT num_cerdos FROM corrales WHERE numero_corral = $num_corral AND caseta_id = $num_caseta";
    $resultado = $conexion->query($query_verificar);
    $fila = $resultado->fetch_assoc();
    $num_cerdos_actual = $fila['num_cerdos'] ?? 0;

    if ($num_cerdos_actual > 0) {
        // Eliminar un cerdo
        $query_eliminar = "UPDATE corrales SET num_cerdos = num_cerdos - 1 WHERE numero_corral = $num_corral AND caseta_id = $num_caseta";
        if ($conexion->query($query_eliminar) === TRUE) {
            // Registrar en eliminacion_muerte
            $query_insertar_muerte = "INSERT INTO eliminacion_muerte (fecha_muerte, num_caseta, num_corral, causa_muerte, usuario) 
                                      VALUES ('$fecha_muerte', $num_caseta, $num_corral, '$causa_muerte', '$usuario')";
            $conexion->query($query_insertar_muerte);

            // Registrar en historial
            $accion = "Eliminó un cerdo por muerte en la caseta $num_caseta, corral $num_corral. Causa: $causa_muerte";
            $query_historial = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES ('$accion', '$fecha_hora', '$usuario')";
            $conexion->query($query_historial);
        }
    } else {
        echo "<script>alert('No hay cerdos en el corral seleccionado.'); window.location.href='cerdos.php';</script>";
        exit();
    }

} elseif ($_POST['tipo_eliminacion'] == 'venta') {
    // Eliminación por Venta
    $fecha_venta = $_POST['fecha_venta'];
    $cantidad = $_POST['cantidad'];
    $num_caseta = $_POST['num_caseta_venta'];
    $num_corral = $_POST['num_corral_venta'];

    // Consultar cuántos cerdos hay en el corral
    $query_verificar = "SELECT num_cerdos FROM corrales WHERE numero_corral = $num_corral AND caseta_id = $num_caseta";
    $resultado = $conexion->query($query_verificar);
    $fila = $resultado->fetch_assoc();
    $num_cerdos_actual = $fila['num_cerdos'] ?? 0;

    if ($cantidad > $num_cerdos_actual) {
        echo "<script>alert('No puedes vender más cerdos de los que hay en el corral.'); window.location.href='cerdos.php';</script>";
        exit();
    } elseif ($num_cerdos_actual == 0) {
        echo "<script>alert('No hay cerdos en el corral seleccionado.'); window.location.href='cerdos.php';</script>";
        exit();
    } else {
        // Restar la cantidad de cerdos en el corral
        $query_eliminar = "UPDATE corrales SET num_cerdos = num_cerdos - $cantidad WHERE numero_corral = $num_corral AND caseta_id = $num_caseta";
        
        if ($conexion->query($query_eliminar) === TRUE) {
            // Registrar en eliminacion_venta
            $query_insertar_venta = "INSERT INTO eliminacion_venta (fecha_venta, cantidad, num_caseta, num_corral, usuario) 
                                     VALUES ('$fecha_venta', $cantidad, $num_caseta, $num_corral, '$usuario')";
            $conexion->query($query_insertar_venta);

            // Registrar en historial
            $accion = "Vendió $cantidad cerdos en la caseta $num_caseta, corral $num_corral el $fecha_venta.";
            $query_historial = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES ('$accion', '$fecha_hora', '$usuario')";
            $conexion->query($query_historial);
        }
    }
}

// Redirigir a la página de cerdos
header("Location: cerdos.php");
exit();

$conexion->close();
?>
