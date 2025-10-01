<?php
include("config.php"); // Conexión a la BD ($conexion)

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar que existan los campos
    if (!empty($_POST['fecha']) && !empty($_POST['num_caseta']) && !empty($_POST['nombre'])) {
        
        // Sanitizar entradas
        $fecha = $_POST['fecha'];
        $num_caseta = intval($_POST['num_caseta']);
        $nombre = trim($_POST['nombre']);


      

        // Preparar consulta segura
        $stmt = $conexion->prepare("INSERT INTO vacunas (fecha, num_caseta, nombre) VALUES (?, ?, ?)");
        $stmt->bind_param("sids", $fecha, $num_caseta, $nombre, );

        if ($stmt->execute()) {
            // Registro exitoso
            header("Location: vacunacion.php?msg=agregado");
            exit();
        } else {
            // Error en la inserción
            header("Location: vacunacion.php?msg=error");
            exit();
        }

        $stmt->close();
    } else {
        // Faltan datos
        header("Location: vacunacion.php?msg=incompleto");
        exit();
    }
} else {
    // Si no viene por POST, redirigir
    header("Location: vacunacion.php");
    exit();
}
