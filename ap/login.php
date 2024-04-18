<?php
ob_start();
include("config.php");
$u=isset($_REQUEST['u']) ? $_REQUEST['u'] : NULL;
$c=isset($_REQUEST['c']) ? $_REQUEST['c'] : NULL;

echo $c,"</br>";
echo $u,"</br>";

//tomar valores de la base de datos

$consulta = mysqli_query($conexion, "select u from usuarios where u='$u'");
                    while ($fila=mysqli_fetch_array($consulta))
                            {
                                $us=$fila["u"];
                            }
    echo $us; 
    if($us==NULL){header("location:index.php?valor=1");
        

    }
    
    else if($us!=NULL){
        $consulta = mysqli_query($conexion, "select * from usuarios where c=SHA1('$c')");
                   
                     while ($fila=mysqli_fetch_array($consulta))
                            {
                                $contra=$fila["c"];
                                $nombre=$fila["nombre"];

                            }
                        
                            echo "<br>", $contra;
                            if($contra==NULL){header("location:index.php?valor=1");}
                            if($contra!=NULL){
                                session_start();
                                $_SESSION['nombre']=$nombre;
                                //$_SESSION['tiempo']=time();

                                header("location:principal.php");
                            }

                        }
  ob_end_flush();  
?>
    



