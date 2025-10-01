<?php
include("config.php"); // aquí tienes la conexión $conexion

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']); // sanitizar a número entero

        // Preparar query para evitar inyección SQL
        $stmt = $conexion->prepare("DELETE FROM vacunas WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Eliminado con éxito
            header("Location: vacunacion.php?msg=eliminado");
            exit();
        } else {
            // Error al eliminar
            header("Location: vacunacion.php?msg=error");
            exit();
        }

        $stmt->close();
    } else {
        header("Location: vacunacion.php?msg=invalid");
        exit();
    }
} else {
    header("Location: vacunacion.php");
    exit();
}
