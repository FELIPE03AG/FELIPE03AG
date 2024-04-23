<?php
// Iniciar buffer de salida, aunque puede ser innecesario si no se está manipulando la salida
ob_start();

// Incluir configuración para la conexión a la base de datos
include("config.php");

// Recuperar el correo de la solicitud HTTP y validar que no esté vacío
$correo = isset($_REQUEST['correo']) ? trim($_REQUEST['correo']) : NULL;



// Escapar el valor del correo para evitar inyección SQL
//$correo = mysqli_real_escape_string($conexion, $correo);

// Realizar la consulta para obtener el usuario por correo
$consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE co = '$correo'");

                    while ($fila=mysqli_fetch_array($consulta))
                            {
                                $cor=$fila["co"];
                                $idu=$fila["id"];
                            }



// Verificar si la consulta fue exitosa
if ($cor==null) {
    header("Location: index.php?valor=2");
}
else{

// Verificar si se encontraron resultados


    // Generar un código aleatorio de 10 caracteres
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = substr(str_shuffle($permitted_chars), 0, 10);

    // Construir el enlace
    $enlace = "http://localhost:8888/p/camcon.php?cod='$codigo'";
    echo "Enlace: $enlace<br/>";
echo $idu, $cor;
    // Insertar en la base de datos
    mysqli_query($conexion, "insert into solcon (idu, codigo, corr) values('$idu','$codigo', '$cor')");
   // $insert_query = "INSERT INTO solcon (idu, codigo, corr) VALUES ('$idu', '$codigo', '$cor')";
  

   header("Location: index.php?cod=$codigo");

} 

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

// Limpiar el buffer de salida, si es necesario
ob_end_flush();

