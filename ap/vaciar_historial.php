<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('Location: index.php');
    exit();
}

include ("config.php"); // <-- asegúrate que $conexion exista

$usuario = $_SESSION['nombre'];

// 1) Guardar cuántos registros había (opcional)
$resCount = $conexion->query("SELECT COUNT(*) AS total FROM historial");
$row = $resCount->fetch_assoc();
$total = $row['total'];

// 2) TRUNCATE
if ($conexion->query("TRUNCATE TABLE historial")) {

    // 3) Insertar registro indicando quién lo vació
    $accion = "Vació el historial de forma PERMANENTE. Registros eliminados: $total";
    $fecha_hora = date('Y-m-d H:i:s');

    $sql = "INSERT INTO historial (accion, fecha_hora, usuario) 
            VALUES ('$accion', '$fecha_hora', '$usuario')";
    mysqli_query($conexion, $sql);

    header("Location: acciones_usuarios.php?msg=vaciado_ok");
    exit();
} else {
    error_log("Error truncando historial: " . $conexion->error);
    header("Location: acciones_usuarios.php?msg=error_vaciado");
    exit();
}
