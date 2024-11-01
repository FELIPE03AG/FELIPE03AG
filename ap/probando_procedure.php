
<?php
// Incluir configuración para la conexión a la base de datos
include("config.php");

// Recibir los datos del formulario
$cantidad = isset($_POST['num_cerdos']) ? $_POST['num_cerdos'] : '';
$caseta = isset($_POST['num_caseta']) ? $_POST['num_caseta'] : '';
$fecha = isset($_POST['fecha_llegada_cerdos']) ? $_POST['fecha_llegada_cerdos'] : '';
$peso = isset($_POST['peso_prom']) ? $_POST['peso_prom'] : '';
$edad = isset($_POST['edad_prom']) ? $_POST['edad_prom'] : '';
$etapa = isset($_POST['etapa_inicial']) ? $_POST['etapa_inicial'] : '';

// Validar la edad
if (!is_numeric($edad) || $edad < 0 || $edad > 100) {
    header("Location: cerdos.php?error=edad_invalida");
    exit();
}

// Imprimir los valores para depuración
echo "Cantidad: $cantidad, Caseta: $caseta, Fecha: $fecha, Peso: $peso, Edad: $edad, Etapa: $etapa";

// Ejecutar el procedimiento almacenado
$consulta = "CALL insertar_cerdos(?, ?, ?, ?, ?, ?)";
$intenta = $conexion->prepare($consulta);
$intenta->bind_param("iisisi", $cantidad, $caseta, $fecha, $peso, $edad, $etapa);

if ($intenta->execute()) {
    // Registro en la tabla de historial
    session_start();
    $usuario = $_SESSION['nombre']; // Cambiar de $_SESSION['u'] a $_SESSION['nombre']
    $accion = "Agregó un nuevo registro en la tabla de cerdos";
    $fecha_hora = date('Y-m-d H:i:s');
    $registro = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES ('$accion', '$fecha_hora', '$usuario')";
    mysqli_query($conexion, $registro);

    // La consulta se realizó con éxito, redireccionar a la página de cerdos
    header("Location: cerdos.php");
    exit();
} else {
    echo "Error al ejecutar la consulta: " . $intenta->error; // Mostrar el error específico
}

// Cerrar la conexión
$conexion->close();
