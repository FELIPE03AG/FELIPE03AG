<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AP</title>
    <script src="https://www.google.com/recaptcha/api.js?render=6LeDJZ4pAAAAAO4I7EBvtsTm5qEm2p85sJYFwjqq"></script>

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>

    <script>
        function ejecutarRecaptcha() {
            grecaptcha.ready(function() {
                // Realiza la llamada para obtener la puntuación del usuario
                grecaptcha.execute('6LeDJZ4pAAAAAO4I7EBvtsTm5qEm2p85sJYFwjqq', {action: 'homepage'}).then(function(token) {
                    // Envía el token al servidor para su procesamiento
                    // Por ejemplo, puedes enviarlo con una solicitud AJAX
                });
            });
        }
    </script>
</head>
<body onload="ejecutarRecaptcha()">
    <div class="text-center">
        <div>
            <br>
            <br>
            <br>
        </div>
        <img src="logotipo.png" width="300px"/>

        <form id="login-form" method="post" action="login.php">
            <!-- Email input -->
            <div class="form-outline mb-4">
                <input type="username" name="u" id="form2Example1" class="form-control" />
                <label class="form-label" for="form2Example1" style="color: white;">Usuario</label>
            </div>
        
            <!-- Password input -->
            <div class="form-outline mb-4">
                <input type="password" name="c" id="form2Example2" class="form-control" />
                <label class="form-label" for="form2Example2"style="color: white;">Contraseña</label>
            </div>

            <?php
            ob_start();
            $valor = isset($_REQUEST['valor']) ? $_REQUEST['valor']: NULL;
            $codigo = isset($_REQUEST['cod']) ? $_REQUEST['cod']: NULL;
            include("config.php");

            $showModal = false;

            
           
            if($valor == 1) {
                echo'<div class="alert alert-danger" role="alert" style="text-align: center;">
                    ***Datos Incorrectos***
                </div>';
            }

            if($valor == 2)
            {
              echo'<div class="alert alert-danger" role="alert" style="text-align: center;">
             ***Correo invalido***
              </div>';

            }

            if($codigo != NULL) {
                $consulta = mysqli_query($conexion, "SELECT * FROM solcon WHERE codigo = '$codigo'");
                while ($fila=mysqli_fetch_array($consulta)) {
                    $idu=$fila["idu"];
                }

                if($idu==null){echo"error";}
                else{
                  echo $idu;
                  
                    // aparecera el modal o pagina para cambiar la, contrase;a enviar a otro archivo que cambie la contrase;a 
                    //segun el id u
                }
                
                            
             
             
             

            }
            

            ob_end_flush();  
            ?>
          
        
          <!-- 2 column grid layout for inline styling -->
          <div class="row mb-4">
            <div class="col d-flex justify-content-center">
              <!-- Checkbox -->
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                <label class="form-check-label" for="form2Example31"style="color: white;"> Recuerdame </label>
              </div>
            </div>
        
            <div class="col">
              <!-- Simple link -->
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Olvide mi Contraseña
              </button>

            </div>
          </div>
        
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4"style="color: white;">Iniciar sesión</button>
        
            <!-- Register button -->
            <div class="text-center">
                <p> <label style ="color: white"> No tiene cuenta? <a href="register.php"style="color: white;">Registrarme</a> </label></p>
            </div>
        </form>


      </div>

</body>
<!-- Modal 1 -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Recuperar Contraseña</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="recon.php" id="formul">
                    <div class="form-outline mb-4">
                        <input type="email" id="form2Examplell" name="correo" class="form-control" placeholder="correo electronico"/>
                        <label class="form-label" for="form2Examplell">Correo electronico</label>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" form="formul" class="btn btn-primary"></button>
            </div>
        </div>
    </div>
</div>





 




<script src="js/snippets.js"></script>
<script src="js/modals.js"></script>

</html>