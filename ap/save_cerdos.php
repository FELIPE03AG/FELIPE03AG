<?php
// Conexión a la base de datos
include("config.php");

$conexion = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos del formulario
$caseta = $_POST['caseta'];
$num_cerdos = $_POST['num_cerdos'];
$peso_prom = $_POST['peso_prom'];
$edad_prom = $_POST['edad_prom'];
$fecha_llegada = $_POST['fecha_llegada'];
$etapa = $_POST['etapa'];

// Insertar en la tabla "casetas"
$sql_caseta = "INSERT INTO casetas (nombre, num_cerdos, peso_promedio, edad_promedio, fecha_llegada, etapa_alimentacion) 
               VALUES ('Caseta $caseta', $num_cerdos, $peso_prom, $edad_prom, '$fecha_llegada', '$etapa')";

if ($conexion->query($sql_caseta) === TRUE) {
    // Obtener el ID de la caseta recién creada
    $caseta_id = $conexion->insert_id;

    // Insertar los corrales relacionados
    for ($i = 1; $i <= 30; $i++) {
        $num_cerdos_corral = isset($_POST["corral_$i"]) ? (int)$_POST["corral_$i"] : 0;

        $sql_corral = "INSERT INTO corrales (numero_corral, num_cerdos, caseta_id) 
                       VALUES ($i, $num_cerdos_corral, $caseta_id)";
        
        if (!$conexion->query($sql_corral)) {
            echo "Error al insertar el corral $i: " . $conexion->error . "<br>";
        }
    }

    echo "Registro guardado correctamente.";
    header("Location: admin_cerdos.php"); // Redirigir a la página principal o lista de casetas
    exit;
} else {
    echo "Error al guardar la caseta: " . $conexion->error;
}

$conexion->close();
?>
