<?php
session_start();
include("config.php");

if (!isset($_POST['tipo_eliminacion'])) {
    die("No se recibió tipo de eliminación. Datos POST: " . print_r($_POST, true));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cerdos.php');
    exit();
}

$usuario = $_SESSION['nombre'];
$fecha_hora = date('Y-m-d H:i:s');

// =====================================================
// ============ ELIMINACIÓN POR MUERTE =================
// =====================================================

if ($_POST['tipo_eliminacion'] == 'muerte') {

    $fecha_muerte = $_POST['fecha_muerte'];
    $num_caseta = $_POST['num_caseta_muerte'];
    $num_corral = $_POST['num_corral_muerte'];
    $causa_muerte = $_POST['causa_muerte'];

    $query_verificar = "SELECT num_cerdos FROM corrales 
                        WHERE numero_corral = $num_corral 
                        AND caseta_id = $num_caseta";

    $resultado = $conexion->query($query_verificar);
    $fila = $resultado->fetch_assoc();
    $num_cerdos_actual = $fila['num_cerdos'] ?? 0;

    if ($num_cerdos_actual == 0) {
        header("Location: elim_cerdosMuerte.php?error=vacio");
        exit();
    }

    $query_eliminar = "UPDATE corrales 
                       SET num_cerdos = num_cerdos - 1 
                       WHERE numero_corral = $num_corral 
                       AND caseta_id = $num_caseta";

    if ($conexion->query($query_eliminar) === TRUE) {

        $query_insertar_muerte = "INSERT INTO eliminacion_muerte 
                                  (fecha_muerte, num_caseta, num_corral, causa_muerte, usuario) 
                                  VALUES ('$fecha_muerte', $num_caseta, $num_corral, '$causa_muerte', '$usuario')";
        $conexion->query($query_insertar_muerte);

        $accion = "Eliminó un cerdo por muerte en caseta $num_caseta, corral $num_corral. Causa: $causa_muerte";
        $query_historial = "INSERT INTO historial (accion, fecha_hora, usuario) 
                            VALUES ('$accion', '$fecha_hora', '$usuario')";
        $conexion->query($query_historial);
    } else {
        die("Error al eliminar: " . $conexion->error);
    }

    header("Location: cerdos.php");
    exit();
}



// =====================================================
// ================ ELIMINACIÓN POR VENTA ===============
// =====================================================

if ($_POST['tipo_eliminacion'] == 'venta') {

    $fecha_venta = $_POST['fecha_venta'];
    $cantidad = intval($_POST['cantidad']);
    $num_caseta = $_POST['num_caseta_venta'];
    $num_corral = $_POST['num_corral_venta'];

    $query_verificar = "SELECT num_cerdos FROM corrales 
                        WHERE numero_corral = $num_corral 
                        AND caseta_id = $num_caseta";

    $resultado = $conexion->query($query_verificar);
    $fila = $resultado->fetch_assoc();
    $num_cerdos_actual = $fila['num_cerdos'] ?? 0;

    if ($num_cerdos_actual == 0) {
        header("Location: elim_cerdosVenta.php?error=vacio");
        exit();
    }

    if ($cantidad > $num_cerdos_actual) {
        header("Location: elim_cerdosVenta.php?error=exceso");
        exit();
    }

    $query_eliminar = "UPDATE corrales 
                       SET num_cerdos = num_cerdos - $cantidad 
                       WHERE numero_corral = $num_corral 
                       AND caseta_id = $num_caseta";

    if ($conexion->query($query_eliminar) === TRUE) {

        $query_insertar_venta = "INSERT INTO eliminacion_venta 
                                 (fecha_venta, cantidad, num_caseta, num_corral, usuario) 
                                 VALUES ('$fecha_venta', $cantidad, $num_caseta, $num_corral, '$usuario')";
        $conexion->query($query_insertar_venta);

        $accion = "Vendió $cantidad cerdos en caseta $num_caseta, corral $num_corral.";
        $query_historial = "INSERT INTO historial (accion, fecha_hora, usuario) 
                            VALUES ('$accion', '$fecha_hora', '$usuario')";
        $conexion->query($query_historial);

    } else {
        die("Error al eliminar: " . $conexion->error);
    }

    header("Location: cerdos.php");
    exit();
}

$conexion->close();
?>