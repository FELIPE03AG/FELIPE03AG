<?php
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
    header('location:index.php');
}

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

echo $rol;

?>
<?php
include("config.php");

if (isset($_POST['id_registro']) && isset($_POST['numCaseta'])) {
    $id_registro = $_POST['id_registro'];
    $numCaseta = $_POST['numCaseta'];

    $query = "UPDATE cerdos SET num_cerdos = num_cerdos - 1 WHERE num_caseta = ? AND id_registro = ? AND num_cerdos > 0";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $numCaseta, $id_registro);

    if ($stmt->execute()) {
        echo "Cerdos actualizados correctamente en la tabla 'cerdos'.";
    } else {
        echo "Error al actualizar el número de cerdos en la tabla 'cerdos'.";
    }

    $stmt->close();
} else {
    echo "Parámetros no válidos.";
}

$conexion->close();
?>
