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
// Conexión a la base de datos
include 'conexion.php'; // Archivo que conecta con la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idRegistro = $_POST['id_registro'];  // El ID del registro de la caseta
    $corral = $_POST['corral'];  // El número de corral
    $cerdos = $_POST['cerdos'];  // El nuevo número de cerdos en ese corral
    $totalCerdos = $_POST['total_cerdos'];  // El nuevo número total de cerdos

    // Actualizar el número total de cerdos en la caseta
    $sqlTotal = "UPDATE casetas SET num_cerdos = $totalCerdos WHERE id_registro = $idRegistro";
    $resultadoTotal = mysqli_query($conexion, $sqlTotal);

    // Si necesitas tener un registro de cuántos cerdos hay en cada corral
    // (Esto depende de cómo tengas organizada la base de datos)
    $sqlCorral = "UPDATE corrales SET num_cerdos = $cerdos WHERE id_registro = $idRegistro AND corral = $corral";
    $resultadoCorral = mysqli_query($conexion, $sqlCorral);

    if ($resultadoTotal && $resultadoCorral) {
        echo "Actualización exitosa.";
    } else {
        echo "Error en la actualización: " . mysqli_error($conexion);
    }

    mysqli_close($conexion);
}
?>
