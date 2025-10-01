<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
    exit();
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar que existan los campos
    if (!empty($_POST['fecha']) && !empty($_POST['num_caseta']) && !empty($_POST['cantidad']) && !empty($_POST['etapa'])) {
        
        // Sanitizar entradas
        $fecha = $_POST['fecha'];
        $num_caseta = intval($_POST['num_caseta']);
        $cantidad = floatval($_POST['cantidad']);
        $etapa = $_POST['etapa'];

         // Validaciones
    if ($num_caseta < 1 || $num_caseta > 6) {
        header("Location: alimento.php?msg=caseta_invalida");
        exit();
    }

    if ($cantidad < 0 || $cantidad > 5) {
        header("Location: alimento.php?msg=cantidad_invalida");
        exit();
    }


        // Preparar consulta segura para la tabla tolvas
        $stmt = $conexion->prepare("INSERT INTO tolvas (fecha, num_caseta, cantidad, etapa) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sids", $fecha, $num_caseta, $cantidad, $etapa);

        // Guardar el registro en el historial
        $accion = "Agregó un nuevo registro de Tolva";
        $fecha_hora = date('Y-m-d H:i:s');
        $usuario = $_SESSION['nombre'];

        $stmtHist = $conexion->prepare("INSERT INTO historial (accion, fecha_hora, usuario) VALUES (?, ?, ?)");
        $stmtHist->bind_param("sss", $accion, $fecha_hora, $usuario);
        $stmtHist->execute();
        $stmtHist->close();

        if ($stmt->execute()) {
            // Registro exitoso
            header("Location: alimento.php?msg=agregado");
            exit();
        } else {
            // Error en la inserción
            header("Location: alimento.php?msg=error");
            exit();
        }

        $stmt->close();
    } else {
        // Faltan datos
        header("Location: alimento.php?msg=incompleto");
        exit();
    }
} else {
    // Si no viene por POST, redirigir
    header("Location: alimento.php");
    exit();
}
?>
