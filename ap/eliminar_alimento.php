<?php
include("config.php");// archivo de conexión

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM tolvas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Registro eliminado correctamente');window.location='index.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "ID no especificado.";
}
?>
