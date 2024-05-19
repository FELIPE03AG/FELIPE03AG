<?php
// Incluir configuración para la conexión a la base de datos
include("config.php");

// Verificar si se recibieron los datos del formulario de edición
if(isset($_POST['id_registro']) && isset($_POST['num_caseta']) && isset($_POST['num_cerdos']) && isset($_POST['fecha_llegada_cerdos']) && isset($_POST['peso_prom']) && isset($_POST['edad_prom']) && isset($_POST['etapa_inicial'])) {
    // Obtener los datos del formulario
    $id_registro = $_POST['id_registro'];
    $num_caseta = $_POST['num_caseta'];
    $num_cerdos = $_POST['num_cerdos'];
    $fecha_llegada_cerdos = $_POST['fecha_llegada_cerdos'];
    $peso_prom = $_POST['peso_prom'];
    $edad_prom = $_POST['edad_prom'];
    $etapa_inicial = $_POST['etapa_inicial'];

    // Verificar si la caseta ya está ocupada por otro registro
    $query = "SELECT id_registro FROM cerdos WHERE num_caseta = '$num_caseta' AND id_registro != '$id_registro'";
    $result = mysqli_query($conexion, $query);
    $caseta_existente = mysqli_num_rows($result) > 0;

    if ($caseta_existente) {
        // Si el número de caseta ya está ocupado, redireccionar a la página anterior mostrando el mensaje de error
        header("Location: cerdos.php?error=caseta_existente");
        exit();
    } else {
        // La caseta está disponible, actualizar el registro en la base de datos
        $query = "UPDATE cerdos SET num_caseta='$num_caseta', num_cerdos='$num_cerdos', fecha_llegada_cerdos='$fecha_llegada_cerdos', peso_prom='$peso_prom', edad_prom='$edad_prom', etapa_inicial='$etapa_inicial' WHERE id_registro='$id_registro'";

        // Ejecutar la consulta
        if(mysqli_query($conexion, $query)) {
            // Registro en la tabla de historial
            session_start();
            $usuario = $_SESSION['nombre']; // Cambiar de $_SESSION['u'] a $_SESSION['nombre']
            $accion = "Editó un registro en la tabla de cerdos";
            $fecha_hora = date('Y-m-d H:i:s');
            $registro = "INSERT INTO historial (accion, fecha_hora, usuario) VALUES ('$accion', '$fecha_hora', '$usuario')";
            mysqli_query($conexion, $registro);

            // Redirigir al usuario a la página principal con un mensaje de éxito
            header("Location: cerdos.php?success=registro_editado");
            exit();
        } else {
            // Redirigir al usuario a la página principal con un mensaje de error
            header("Location: cerdos.php?error=edicion_fallida");
            exit();
        }
    }
} else {
    // Redirigir al usuario a la página principal si no se recibieron los datos del formulario
    header("Location: cerdos.php");
    exit();
}
?>
