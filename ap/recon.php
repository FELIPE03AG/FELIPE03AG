<?php
ob_start();
include ("config.php");

$correo=isset($_REQUEST['correo']) ? $_REQUEST['correo'] : NULL;
echo $correo, "</br>";

$consulta = mysqli_query($conexion, "select * from usuarios where correo='$correo'");
while ($fila=mysqli_fetch_array($consulta))
{
$correo=$fila["co"];
$idu=$fila["id"];
}


if($correo==NULL){header("location:index.php?valor=2");}
else{

$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
// Output: 54esmdr0qf
$codigo= substr(str_shuffle($permitted_chars), 0, 10);
$enlace="http://localhost:8888/p/camcon.php?cod='$codigo'";
echo $enlace;
$consulta = mysqli_query($conexion, "insert into solcon (idu, codigo, corr) values('$idu','$codigo', '$correo')");
/*
$para = $correo;
$titulo = 'Recuperar contraeña empresa';
$mensaje = '
SE solicto blabla
ingresa al siguiente enlace para recuperar:
'.$enlace.'
';
$cabeceras = 'From: chido@empresa.com' . "\r\n" .
'Reply-To: webmaster@empresa.com' . "\r\n" .
'X-Mailer: PHP/' . phpversion();

mail($para, $titulo, $mensaje, $cabeceras);

*/
}


/*
//tomar de la base de datos el valor de usuario guardar en$ us

$consulta = mysqli_query($conexion, "select u from usuarios where u='$u'");
while ($fila=mysqli_fetch_array($consulta))
{
$us=$fila["u"];
}
echo $us;
if($us==NULL){header("location:index.php?valor=1");}
else if($us!=NULL){
$consulta = mysqli_query($conexion, "select * from usuarios where c=SHA1('$c')");
while ($fila=mysqli_fetch_array($consulta))
{
$co=$fila["c"];
$nombre=$fila["nombre"];
}
echo "<br>", $co;

if($co==NULL){header("location:index.php?valor=1");}
if($co!=NULL){
session_start();
$_SESSION['nombre']=$nombre;
//$_SESSION['tiempo']=time();
header("location:principal.php");
}


//




}
//si si crear sesiones y enviar a pagina principal






*/

ob_end_flush();
?>
